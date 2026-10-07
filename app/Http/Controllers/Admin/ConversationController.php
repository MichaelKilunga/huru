<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        $query = Message::query()->with(['user', 'aiLog', 'feedback'])->latest('id');

        if ($request->filled('q')) {
            $q = '%' . $request->string('q') . '%';
            $query->where(fn ($w) => $w->where('content', 'like', $q)->orWhereHas('user', fn ($u) => $u->where('phone_number', 'like', $q)->orWhere('name', 'like', $q)));
        }
        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }
        if ($request->filled('channel')) {
            $query->where('channel', $request->string('channel'));
        }
        if ($request->filled('direction')) {
            $query->where('direction', $request->string('direction'));
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->string('date'));
        }

        $messages = $query->paginate(40)->withQueryString();

        return view('admin.conversations.index', compact('messages'));
    }

    public function show(User $user)
    {
        $messages = $user->messages()->with(['aiLog', 'feedback'])->latest('id')->paginate(60);

        return view('admin.conversations.show', compact('user', 'messages'));
    }
}
