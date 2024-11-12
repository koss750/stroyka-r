<?php

namespace App\Http\Controllers;

use App\Models\User;
use Musonza\Chat\Facades\ChatFacade as Chat;

class ChatController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(1); // Default to user 1
        $conversations = Chat::conversations()->setParticipant($user)->get();
        
        return view('vora.chat', compact('conversations'));
    }

    public function show($conversationId)
    {
        $user = User::findOrFail(1); // Default to user 1
        $conversation = Chat::conversations()->getById($conversationId);
        $messages = Chat::conversations()->setParticipant($user)->get();

        return view('vora.showchats', compact('conversation', 'messages'));
    }

}
