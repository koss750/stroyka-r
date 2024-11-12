@extends('layouts.alternative')


@section('additional_head')
<title>{{ $page_title }} - Стройка.com</title>
<meta name="description" content="{{ $page_description }}">
<link rel="canonical" href="{{ url()->current() }}" />
@endsection

   @php
   //todo: add sorting by price/area to designs
   @endphp

    <main id="afterHeader">
        <div id="loadingSpinner" class="text-center" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000;">
            <div class="spinner-border" role="status">
                <span class="sr-only">Загрузка...</span>
            </div>
            <p>Загрузка</p>
        </div>

        <div class="container" id="designsContainer" style="display: none; margin-top: 94px;">
            @include('partials.tab-navigator', ['items' => [
                ['url' => '/browse/doma_iz_brusa', 'label' => 'Дома из бруса'],
                ['url' => '/browse/doma_iz_brevna', 'label' => 'Дома из бревна'],
                ['url' => '/browse/bani_iz_brusa', 'label' => 'Бани из бруса'],
                ['url' => '/browse/bani_iz_brevna', 'label' => 'Бани из бревна'],
            ]])
            <h1 class="sr-only">Каталог проектов домов и бань</h1>
            
            <div class="sorting-controls mb-3">
                <nav aria-label="sorting options">
                    <ul class="nav nav-tabs custom-tabs justify-content-center">
                        <li class="nav-item">
                            <span class="nav-link disabled">Сортировать по:</span>
                        </li>
                        
                        <li class="nav-item">
                            <span class="nav-link disabled">Площади:</span>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link sort-btn" data-sort="size-asc">
                                <i class="fas fa-caret-up"></i> 
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link sort-btn" data-sort="size-desc">
                                <i class="fas fa-caret-down"></i> 
                            </a>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link disabled">Цене:</span>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link sort-btn" data-sort="price-asc">
                                <i class="fas fa-caret-up"></i> 
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link sort-btn" data-sort="price-desc">
                                <i class="fas fa-caret-down"></i> 
                            </a>
                        </li>
                    </ul>
                </nav>
                <div class="text-center mt-2">
                    <span id="sortingSpinner" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Сортировка...
                    </span>
                </div>
            </div>

            <div class="row" id="designsRow">
                @foreach ($designs as $design)
                <div class="col-sm-6 design-item" data-size="{{ $design->size }}" data-price="{{ (float) str_replace(',', '', $design->price) }}">
                    <a href="{{ url('project', $design->slug) }}" class="flipCard-link">
                        <article class="flipCard">
                            <div class="flipCard-Image">
                                <img data-src="{{ $design->image_url ? asset($design->image_url) : '' }}" alt="{{ $design->title }}" class="skeleton" loading="lazy">
                            </div>
                            <div class="flipCard-Text">
                                <h3 class="title">{{ $design->title }}m<sup>2</sup></h3>
                                <h3 class="price">{{ $design->price }} руб.</h3>
                            </div>
                        </article>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </main>

    <style>
    @media (max-width: 767px) {
        .sorting-controls .nav-item {
            width: 100%;
            text-align: center;
        }
        
        /* Group arrows with their labels */
        .sorting-controls .nav-item:has(.sort-btn) {
            display: inline-block;
            width: auto;
        }
        
        /* Add margin to create separation between groups */
        .sorting-controls .nav-item:has(.disabled) {
            margin-top: 10px;
        }
    }
    
    .sorting-controls .nav-tabs {
        border-bottom: none;
    }
    .sorting-controls .nav-link {
        color: #495057;
        background-color: #fff;
        border: 1px solid #dee2e6;
        margin: 0 5px;
    }
    .sorting-controls .nav-link:hover:not(.disabled) {
        color: #007bff;
        border-color: #007bff;
    }
    .sorting-controls .nav-link.active {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }
    .sorting-controls .nav-link.disabled {
        color: #6c757d;
        pointer-events: none;
    }
    </style>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const designsContainer = document.getElementById('designsContainer');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const images = designsContainer.querySelectorAll('img');

        function showContent() {
            loadingSpinner.style.display = 'none';
            designsContainer.style.display = 'block';
            designsContainer.classList.add('show');
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

        // Sorting functionality
        const designsRow = document.getElementById('designsRow');
        const sortButtons = document.querySelectorAll('.sort-btn');
        const sortingSpinner = document.getElementById('sortingSpinner');

        sortButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all buttons
                sortButtons.forEach(btn => btn.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');

                const sortType = this.dataset.sort;
                const designs = Array.from(designsRow.querySelectorAll('.design-item'));

                // Show spinner
                sortingSpinner.style.display = 'inline-block';

                setTimeout(() => {
                    designs.sort((a, b) => {
                        let aValue, bValue;
                        if (sortType.startsWith('size')) {
                            aValue = parseFloat(a.dataset.size);
                            bValue = parseFloat(b.dataset.size);
                        } else {
                            aValue = parseFloat(a.dataset.price);
                            bValue = parseFloat(b.dataset.price);
                        }

                        if (sortType.endsWith('asc')) {
                            return aValue - bValue;
                        } else {
                            return bValue - aValue;
                        }
                    });

                    designs.forEach(design => designsRow.appendChild(design));

                    // Hide spinner after sorting is complete
                    sortingSpinner.style.display = 'none';
                }, 0);
            });
        });

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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if there's a hash in the URL
        if (window.location.hash === '#container_new_section') {
            // Remove the hash from the URL
            history.replaceState(null, document.title, window.location.pathname);
            
            // Scroll to the section
            const section = document.querySelector('.container_new_section');
            if (section) {
                setTimeout(function() {
                    section.scrollIntoView({ behavior: 'smooth' });
                }, 100);
            }
        }

        // Add click event listener to all links with the 'scroll-to-section' class
        document.querySelectorAll('.scroll-to-section').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                const isInternalLink = href.startsWith('#') || href.includes(window.location.origin);

                if (isInternalLink) {
                    e.preventDefault();
                    const targetId = href.split('#')[1];
                    const targetElement = document.querySelector('.' + targetId);

                    if (targetElement) {
                        targetElement.scrollIntoView({ behavior: 'smooth' });
                    } else {
                        // If the target is not on this page, navigate to the link
                        window.location.href = href;
                    }
                }
            });
        });
    });
    </script>
</body>
</html>
