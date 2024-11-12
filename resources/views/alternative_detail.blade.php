@extends('layouts.alternative')


@section('canonical', '')


@section('additional_head')
<link rel="canonical" href="{{ url()->current() }}" />
<title>Стройка.com - {{ $page_title }}</title>
<meta name="description" content="{{ $page_description }}">

@foreach($turboPageMeta as $property => $content)
        <meta property="{{ $property }}" content="{{ $content }}">
@endforeach
<script src="https://xn--80ardojfh.com/assets/js/detail.js"></script>
<script>
    var selectedOptions = {
        foundation: '',
        dd: '',
        roof: ''
    };

    var selectedOptionRefs = {
        foundation: 220,
        dd: {{ $design->defaultRef }},
        roof: 222
    };

    var selectedOptionPrices = {
        foundation: 0,
        dd: 0,
        roof: 0
    };
    document.addEventListener('DOMContentLoaded', function() {

        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        for (let optionType in selectedOptionRefs) {
        let defaultElement = document.querySelector(`[data-ref="${selectedOptionRefs[optionType]}"]`);
        if (defaultElement) {
                    updateSelectedOption(defaultElement, optionType, defaultElement.getAttribute('data-ref'));
                }
            }
        toggleOptions({{ $design->defaultParent }}, {{ $design->defaultRef }}, 'dd');
        let defaultElementDD = document.getElementById('dd_' + {{ $design->defaultParent }});
        defaultElementDD.checked = true;
    });
</script>
<div id="mobilePriceBar" class="mobile-price-bar">
Стоимость выбранной комплектации: <span id="mobileTotalPrice">{{ $design->etiketka }}</span>
</div>
@endsection

@section('sub_heading', 'Проект дома ' . $design->length . ' x ' . $design->width)

            @section('content')
            <div class="row" style="margin-top: 30px;">
            @include('partials.tab-navigator', ['items' => [
            ['url' => '../browse/doma_iz_brusa', 'label' => 'Дома из бруса'],
            ['url' => '../browse/doma_iz_brevna', 'label' => 'Дома из бревна'],
            ['url' => '../browse/bani_iz_brusa', 'label' => 'Бани из бруса'],
            ['url' => '../browse/bani_iz_brevna', 'label' => 'Бани из бревна'],
        ]])
               <div class="col-lg-6 col-md-12">
               <div class="price-tag flipCard-title text-black">
                        <h2>Проект {{ $design->title }}m2 <br class="mobile-break"> {{ $design->length }}m x {{ $design->width}}m</h2>
                    </div>
                <x-image-carousel :jpgImageUrls="$jpgImageUrls" :thumbImageUrls="$thumbImageUrls" />
                    
                  
                  <div class="col-12 text-center">
                  <button class="btn btn-outline-light buttons" data-toggle="modal" data-target="#exampleModal" data-bs-toggle="modal" data-bs-target="#exampleModal">Пример сметы</button>
                  </div>
                  <div class=" col-12 text-center">
                  <button type="button" class="btn btn-outline-light buttons" data-toggle="modal" data-target="#paymentModal" data-bs-toggle="modal" data-bs-target="#paymentModal">
                Купить смету
                </button>
                  </div>
               </div>
               <div class="col-lg-6 col-md-12">
               <div class="price-tag text-black">
                    <h2>Стоимость: <span id="totalPrice">{{ $design->etiketka }}</span>
                        <i class="fas fa-info-circle text-danger" data-toggle="tooltip" data-placement="top" title="{{$toolTipLabel}}" style="
    font-size: 48%;
    vertical-align: top;
    padding-top: 5px;
"></i>
                    </h2>
                </div>
               

               @component('components.optionGroup', [
                    'groupName' => 'foundation',
                    'options' => $nestedData['foundations'],
                    'label' => 'Фундамент'
                ])
                @endcomponent

                @component('components.optionGroup', [
                    'groupName' => 'dd',
                    'options' => $nestedData['dd_options'],
                    'label' => 'Домокомплект'
                ])
                @endcomponent

                @component('components.optionGroup', [
                    'groupName' => 'roof',
                    'options' => $nestedData['roofs'],
                    'label' => 'Кровля'
                ])
                @endcomponent

                    
               </div>
            </div>
      <!-- Payment Modal -->
      @component('components.payment-modal', ['id' => $design->id, 'title' => $design->title, 'image' => $design->image_url, 'price' => $design->smeta_price])
@endcomponent
@component('components.payment-modal', ['id' => 1, 'title' => "Пример сметы", 'image' => $design->image_url, 'price' => 0])
@endcomponent
      <x-modal-carousel />
      @endsection
      @section('additional_scripts')
      
    <script>
let currentImageIndex = 0;
let imageUrls = [];

function openImageModal(imgSrc) {
    var modal = document.getElementById("image_modal");
    var modalImg = document.getElementById("image_modal_img");
    modal.style.display = "block";
    modalImg.src = imgSrc;
    //add display block to id headerBar
    document.getElementById("headerBar").style.display = "none";
    // Find the index of the clicked image
    currentImageIndex = imageUrls.indexOf(imgSrc);
}

function changeModalImage(step) {
    currentImageIndex += step;
    if (currentImageIndex >= imageUrls.length) {
        currentImageIndex = 0;
    } else if (currentImageIndex < 0) {
        currentImageIndex = imageUrls.length - 1;
    }
    document.getElementById("image_modal_img").src = imageUrls[currentImageIndex];
}

document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById("image_modal");
    var span = document.getElementsByClassName("image_modal_close")[0];

    // Populate imageUrls array
    imageUrls = Array.from(document.querySelectorAll('.carousel-item img')).map(img => img.src);

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
        document.getElementById("headerBar").style.display = "";
    }

    // Close the modal when clicking outside the image
    modal.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
            document.getElementById("headerBar").style.display = "";
        }
    }

    // Prevent clicks on the image from closing the modal
    document.getElementById("image_modal_img").onclick = function(event) {
        event.stopPropagation();
    }

    // Add keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (modal.style.display === "block") {
            if (e.key === "ArrowLeft") {
                changeModalImage(-1);
            } else if (e.key === "ArrowRight") {
                changeModalImage(1);
            } else if (e.key === "Escape") {
                modal.style.display = "none";
            }
        }
    });
});
</script>
      <script>
      function updatePrice(newPrice) {
            document.getElementById('totalPrice').textContent = newPrice;
            document.getElementById('mobileTotalPrice').textContent = newPrice;
        }

        function toggleMobilePriceBar() {
            var mobilePriceBar = document.getElementById('mobilePriceBar');
            var header = document.querySelector("header");
            if (window.pageYOffset > header.offsetHeight) {
                mobilePriceBar.style.display = 'block';
                header.style.display = 'none';
            } else {
                mobilePriceBar.style.display = 'none';
                header.style.display = 'block';
            }
        }

        window.onscroll = function() {
            toggleMobilePriceBar();
        };
      </script>
      <style>
        .mobile-top-bar {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: #fff;
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1000;
            text-align: center;
        }

        @media (max-width: 1500px) {
            .mobile-top-bar {
                display: block;
            }
        }

        .mobile-break {
            display: none;
        }

        @media (max-width: 768px) {
            .mobile-break {
                display: inline;
            }
        }

        .mobile-price-bar {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: #fff;
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1000;
            text-align: center;
            font-size: 1.35em;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .mobile-price-bar {
                display: none;
            }
        }
      </style>
@endsection