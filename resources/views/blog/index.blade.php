@extends('layouts.alternative')

@section('canonical', '')

@section('additional_head')
    <title>Блог</title>
    <meta name="title" content="Блог">
    <meta name="canonical" content="{{ route('blog.index') }}">
    <meta name="description" content="Блог">
    <meta name="keywords" content="Блог">
@endsection

@section('content')
        <div id="loadingSpinner" class="text-center" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000;">
            <div class="spinner-border" role="status">
                <span class="sr-only">Загрузка...</span>
            </div>
            <p>Загрузка</p>
        </div>

        <div class="container" id="blogContainer" style="margin-top: 94px;">
            <h1 class="sr-only">Блог</h1>
            
            <div class="row" id="blogPostsRow">
                @foreach ($posts as $post)
                <div class="col-lg-6 col-md-12 blog-item">
                    <a href="{{ route('blog.show', $post->slug) }}" class="flipCard-link">
                        <article class="flipCard">
                            <div class="flipCardBlog-Image">
                                <img data-src="{{ $post->getFirstMediaUrl('images', 'thumb') }}" alt="{{ $post->title }}" class="skeleton" loading="lazy">
                            </div>
                            <div class="flipCard-Text">
                                <h3 class="title">{{ $post->title }}</h3>
                                <p class="description">{{ $post->short_description }} <span class="read-more-blog btn btn-dark">Читать далее..</span></p>
                            </div>
                        </article>
                    </a>
                </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $posts->links() }}
            </div>
        </div>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const blogContainer = document.getElementById('blogContainer');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const images = blogContainer.querySelectorAll('img');

        function showContent() {
            loadingSpinner.style.display = 'none';
            blogContainer.style.display = 'block';
            blogContainer.classList.add('show');
        }

        function loadImage(img) {
            const src = img.dataset.src;
            if (!src) return;

            return new Promise((resolve, reject) => {
                img.onload = resolve;
                img.onerror = reject;
                img.src = src;
            });
        }

        async function loadImagesProgressively() {
            for (const img of images) {
                try {
                    await loadImage(img);
                    img.classList.add('loaded');
                } catch (error) {
                    console.error('Failed to load image:', img.dataset.src);
                }
            }
        }



        // Show content immediately
        showContent();

        // Start loading images progressively
        loadImagesProgressively();

        // Fallback: If loading takes too long, ensure all images are visible
        setTimeout(() => {
            images.forEach(img => {
                if (!img.classList.contains('loaded')) {
                    img.src = img.dataset.src;
                    img.classList.add('loaded');
                }
            });
        }, 5000);
    });
    </script>
    <style>
        .blog-item .description {
            display: none;
        }
        .blog-item:hover .description {
            display: block;
        }
        .blog-item .title {
            display: block;
        }
        .blog-item:hover .title {
            display: none;
        }
    </style>
@endsection
