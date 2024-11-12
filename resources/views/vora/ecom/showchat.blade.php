@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Conversation {{ $conversation->id }}</h2>
    <div>
        @foreach($messages as $message)
            <div>{{ $message->body }}</div>
        @endforeach
    </div>
    <!-- Add form for sending new messages here -->
</div>
@endsection
