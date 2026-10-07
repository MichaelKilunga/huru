<?php

namespace App\Http\Controllers;

use App\Models\CommunityPost;
use App\Models\CommunityThread;
use App\Models\User;
use App\Services\ModerationService;
use App\Support\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CommunityController extends Controller
{
    public function index(Request $request)
    {
        $user = $this->currentUser($request);
        if (! $user) {
            return redirect()->route('chat.index', ['redirect' => $request->getRequestUri()])
                ->with('error', 'Tafadhali ingia kwanza kufikia jamii. / Please sign in to access the community.');
        }

        $publicThreads = CommunityThread::query()->where('is_private', false)->withCount('posts', 'members')->latest()->get();
        $joinedIds = $user->joinedThreads()->pluck('community_threads.id')->all();
        $joinedThreads = $user->joinedThreads()->withCount('posts')->latest()->get();

        return view('community.index', compact('publicThreads', 'joinedThreads', 'joinedIds', 'user') + ['settings' => $this->settings()]);
    }

    public function show(Request $request, CommunityThread $thread)
    {
        $user = $this->currentUser($request);
        if (! $user) {
            return redirect()->route('chat.index', ['redirect' => $request->getRequestUri()]);
        }

        $isMember = $thread->members()->where('user_id', $user->id)->exists();
        if ($thread->is_private && ! $isMember) {
            return redirect()->route('community.index')->with('error', 'Huu ni mjadala wa faragha. / This is a private thread.');
        }

        $posts = $thread->posts()->where('is_approved', true)->with('user')->orderBy('created_at')->paginate(50);
        $members = $thread->members()->get();

        return view('community.show', compact('thread', 'posts', 'members', 'user', 'isMember') + ['settings' => $this->settings()]);
    }

    public function storeThread(Request $request)
    {
        $user = $this->currentUser($request);
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if ($user->is_banned) {
            return back()->with('error', 'Huduma imefungwa kwa akaunti hii.');
        }

        $data = $request->validate([
            'title' => 'required|string|min:3|max:120',
            'description' => 'nullable|string|max:1000',
            'is_private' => 'sometimes|boolean',
        ]);

        $thread = CommunityThread::query()->create([
            'title' => $data['title'],
            'slug' => Str::slug($data['title']) . '-' . Str::lower(Str::random(5)),
            'description' => $data['description'] ?? null,
            'creator_id' => $user->id,
            'is_private' => $request->boolean('is_private'),
        ]);

        $thread->members()->attach($user->id, ['role' => 'admin']);

        return redirect()->route('community.show', $thread);
    }

    public function storePost(Request $request, CommunityThread $thread, ModerationService $moderation)
    {
        $user = $this->currentUser($request);
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if ($user->is_banned) {
            return response()->json(['error' => 'Account blocked'], 403);
        }
        if (! $thread->members()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Si mwanachama wa mjadala huu. / Not a member of this thread.'], 403);
        }

        $data = $request->validate(['content' => 'required|string|min:2|max:3000']);

        if ($moderation->isAbusive($data['content'])) {
            $user->increment('abuse_count');
            if ($user->abuse_count >= Settings::int('abuse_strike_limit')) {
                $user->forceFill(['is_banned' => true])->save();
            }

            return response()->json(['error' => 'Lugha ya matusi hairuhusiwi. / Abusive language is not allowed.'], 422);
        }

        $post = CommunityPost::query()->create([
            'community_thread_id' => $thread->id,
            'user_id' => $user->id,
            'content' => $data['content'],
            'is_approved' => ! $thread->is_system, // system threads are moderated before display on the landing page
        ]);

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success', 'post' => $post->load('user'), 'pending' => ! $post->is_approved]);
        }

        return back()->with('success', $post->is_approved ? 'Ujumbe umetumwa.' : 'Ujumbe umepokelewa na utaonekana baada ya kuhakikiwa.');
    }

    public function join(Request $request, CommunityThread $thread)
    {
        $user = $this->currentUser($request);
        if (! $user) {
            return redirect()->route('chat.index', ['redirect' => route('community.show', $thread, false)]);
        }
        if ($thread->is_private) {
            return back()->with('error', 'Huwezi kujiunga na mjadala wa faragha bila mwaliko.');
        }

        $thread->members()->syncWithoutDetaching([$user->id => ['role' => 'member']]);

        return redirect()->route('community.show', $thread);
    }

    public function leave(Request $request, CommunityThread $thread)
    {
        $user = $this->currentUser($request);
        if (! $user) {
            return redirect()->route('chat.index');
        }

        $thread->members()->detach($user->id);

        return redirect()->route('community.index');
    }

    private function currentUser(Request $request): ?User
    {
        $id = $request->session()->get('chat_user_id');

        return $id ? User::query()->find($id) : null;
    }

    private function settings(): array
    {
        return [
            'app_name' => Settings::get('app_name'),
            'sms_keyword' => Settings::get('sms_keyword'),
            'sms_shortcode' => Settings::get('sms_shortcode'),
        ];
    }
}
