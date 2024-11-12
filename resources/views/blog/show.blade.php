@extends('layouts.alternative')

@section('canonical', '')

@section('additional_head')
    <title>{{ $post->title }}</title>
    <meta name="title" content="{{ $post->title }}">
    <meta name="canonical" content="{{ route('blog.show', $post->slug) }}">
    <meta name="description" content="{{ $post->short_description }}">
    <meta name="keywords" content="{{ implode(', ', $post->tags) }}">
@endsection

@section('content')
    <main id="afterHeader">
        <div class="container" style="margin-top: 94px;">
            <article>
                <h1>{{ $post->title }}</h1>

                <div class="blog-meta">
                    <p>Опубликовано: {{ $post->updated_at->format('d.m.Y') }}</p>
                </div>

                @if($post->hasMedia('images'))
                    <div class="blog-image">
                        <img src="{{ $post->getFirstMediaUrl('images', 'full') }}" alt="{{ $post->title }}" class="img-fluid">
                    </div>
                @endif

                <div class="blog-content">
                    {!! $post->content !!}
                </div>
            </article>

            <div class="mt-4">
                <a href="{{ route('blog.index') }}" class="btn btn-secondary">Назад к списку</a>
            </div>
        </div>
    </main>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
@endsection