<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->where('role', User::ROLE_USER)->withCount('messages')->latest('last_seen_at');

        if ($request->filled('q')) {
            $q = '%' . $request->string('q') . '%';
            $query->where(fn ($w) => $w->where('phone_number', 'like', $q)->orWhere('name', 'like', $q));
        }
        if ($request->boolean('banned')) {
            $query->where('is_banned', true);
        }
        if ($request->filled('region')) {
            $query->where('region', $request->string('region'));
        }

        return view('admin.users.index', ['users' => $query->paginate(40)->withQueryString()]);
    }

    public function toggleBan(User $user)
    {
        abort_if($user->isAdmin(), 403);
        $user->update(['is_banned' => ! $user->is_banned]);

        return back()->with('success', $user->is_banned ? 'User blocked.' : 'User unblocked.');
    }

    public function resetStrikes(User $user)
    {
        $user->update(['abuse_count' => 0, 'is_banned' => false]);

        return back()->with('success', 'Strikes cleared.');
    }

    public function destroy(User $user)
    {
        abort_if($user->isAdmin(), 403);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User and their history deleted.');
    }
}
