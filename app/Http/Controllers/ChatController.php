<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ChatService;

class ChatController extends Controller
{
    private ChatService $chatService;

    public function index()
    {
        return view('chats.index');
    }

    public function show($username)
    {
        $otherUser = User::where('username', $username)->first();
        $currentUser = auth()->user();

        if (! isset($currentUser)
            || ! isset($otherUser)
            || $currentUser->username === $username) {
            return redirect()->route('chats');
        }

        return view('chats.show', compact('otherUser'));
    }

    public function create()
    {
        return view('chats.create');
    }
}
