<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommunityThread;
use App\Models\CommunityPost;
use App\Models\CommunityMember;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    public function index()
    {
        $userId = session('chat_user_id');
        if (!$userId) {
            return redirect()->route('chat.index', ['redirect' => request()->getRequestUri()])->with('error', 'Please login to access the community.');
        }

        $user = User::find($userId);
        
        // Public threads and threads user is a member of
        $publicThreads = CommunityThread::where('is_private', false)->latest()->get();
        $joinedThreads = $user->joinedThreads()->latest()->get();

        return view('community.index', compact('publicThreads', 'joinedThreads'));
    }

    public function show(CommunityThread $thread)
    {
        $userId = session('chat_user_id');
        if (!$userId) {
            return redirect()->route('chat.index', ['redirect' => request()->getRequestUri()]);
        }

        $user = User::find($userId);

        // Check if thread is private and user is a member
        if ($thread->is_private && !$thread->members->contains($user)) {
            return redirect()->route('community.index')->with('error', 'This is a private thread.');
        }

        $posts = $thread->posts()->with('user')->orderBy('created_at', 'asc')->paginate(50);
        $members = $thread->members;

        return view('community.show', compact('thread', 'posts', 'members', 'user'));
    }

    public function storeThread(Request $request)
    {
        $userId = session('chat_user_id');
        if (!$userId) return response()->json(['error' => 'Unauthorized'], 401);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_private' => 'boolean',
        ]);

        $thread = CommunityThread::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . Str::random(5),
            'description' => $request->description,
            'creator_id' => $userId,
            'is_private' => $request->has('is_private'),
        ]);

        // Creator is the first member and admin of the thread
        $thread->members()->attach($userId, ['role' => 'admin']);

        return redirect()->route('community.show', $thread);
    }

    public function storePost(Request $request, CommunityThread $thread)
    {
        $userId = session('chat_user_id');
        if (!$userId) return response()->json(['error' => 'Unauthorized'], 401);

        $user = User::find($userId);
        if (!$thread->members->contains($user)) {
            return response()->json(['error' => 'You are not a member of this thread.'], 403);
        }

        $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        $post = CommunityPost::create([
            'community_thread_id' => $thread->id,
            'user_id' => $userId,
            'content' => $request->content,
            'is_approved' => true, // Auto-approve for now, unless it's a special moderated thread
        ]);

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'post' => $post->load('user')]);
        }

        return back();
    }

    public function join(CommunityThread $thread)
    {
        $userId = session('chat_user_id');
        if (!$userId) return redirect()->route('chat.index', ['redirect' => route('community.show', $thread)]);

        if ($thread->is_private) {
            return back()->with('error', 'Cannot join a private thread without an invite.');
        }

        $thread->members()->syncWithoutDetaching([$userId => ['role' => 'member']]);

        return redirect()->route('community.show', $thread);
    }

    public function leave(CommunityThread $thread)
    {
        $userId = session('chat_user_id');
        if (!$userId) return redirect()->route('chat.index', ['redirect' => route('community.index')]);

        $thread->members()->detach($userId);

        return redirect()->route('community.index');
    }

    // Admin moderation
    public function toggleApproval(CommunityPost $post)
    {
        // This should be protected by admin middleware
        $post->update(['is_approved' => !$post->is_approved]);
        return back()->with('success', 'Post approval toggled.');
    }
}
