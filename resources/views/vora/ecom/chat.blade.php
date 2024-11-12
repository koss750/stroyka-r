@extends('layouts.default')

@section('content')
<div class="container">
    <h1>Chats</h1>
    <ul>
        @foreach($conversations as $conversation)
            <li>
                Conversation ID: {{ $conversation->id }}
                <!-- Add more conversation details here -->
                <a href="{{ route('chats.show', $conversation->id) }}">Open Chat</a>
            </li>
        @endforeach
    </ul>
</div>
@endsection