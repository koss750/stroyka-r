@props(['inn', 'company_name', 'price'])
@if ($price > 0)
    <script src="https://pay.yandex.ru/sdk/v1/pay.js" onload="onYaPayLoad()" async> </script>
@endif
@php
    if ($price == 1) {
        $promo = true;
        $start_date = "today";
        $extension_days = 30;
        $label = "Членство на 1 месяц";
    }
@endphp

<div id="{{ $modal_id }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content modal-content-payment">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">{{ $label }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <form id="{{ $modal_id }}_paymentForm">
                            <div class="form-group" select>
                                <label for="price_type">Включить цены:</label>
                                <select class="form-control" id="price_type" name="price_type" required>
                                    <option value="material" selected>Только материалы</option>
                                    <option value="total" {{ $price == 0 ? 'disabled' : '' }}>Материалы и работы</option>
                                </select>
                            </div>
                            <input type="hidden" name="price" value="{{ $price }}">
                            
                            @if ($price > 0)
                                <!-- Add Yandex Pay button container -->
                                <div id="button_container"></div>
                            @else
                                <button id="example_pay_button" type="button" class="btn btn-outline-light">Заказать пример сметы</button>
                            @endif
                        </form>
                        <div id="orderStatus" class="mt-3"></div>
                    </div>
                    <div class="col-md-7">
                        <h5>Проект {{ $title }}</h5>
                        <img src="{{ $image }}" alt="{{ $title }}" class="img-fluid mb-3">
                        @if ($price > 0)
                        <h5>Индивидуальный расчет по вашим параметрам</h5>
                        @else
                        <h5>Демонстрационная версия</h5>
                        @endif
                        <p class="mt-3">После создания заказа смета будет доступна для скачивания 30 дней через личный кабинет.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .modal-content-payment {
        margin: 5% auto;
        width: 90%;
        max-width: 100%;
    }
    #paymentModal .modal-content {
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    #paymentModal .modal-header {
        border-bottom: none;
        padding: 20px 30px;
    }
    #paymentModal .modal-body {
        padding: 20px 30px;
    }
    #paymentModal .form-control {
        border-radius: 5px;
        border: 1px solid #ced4da;
        padding: 10px 15px;
        line-height: normal;
    }
    #paymentModal .btn-primary {
        background-color: #007bff;
        border: none;
        border-radius: 5px;
        padding: 12px;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }
    #paymentModal .btn-primary:hover {
        background-color: #0056b3;
    }
    .card-type-icon {
        width: 40px;
        height: 25px;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
    }
    .card-type-visa {
        background-image: url('https://www.visa.com.ua/dam/VCOM/regional/ve/romania/blogs/hero-image/visa-logo-800x450.jpg');
    }
    .card-type-mastercard {
        background-image: url('https://platform.vox.com/wp-content/uploads/sites/2/chorus/uploads/chorus_asset/file/13674554/Mastercard_logo.jpg?quality=90&strip=all&crop=0,16.666666666667,100,66.666666666667');
    }
    .card-type-mir {
        background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTtw96jpVRrOTWcYjCpZLNpyV5ESnloAvRU5A&s');
    }

    .input-group-append {
        position: absolute;
        right: 10px;
        top: 19%;
        z-index: 10;
    }

    /* Add Yandex Pay button styles */
    .ya-pay-button {
        height: 40px !important;
        border-radius: 30px !important;
    }

    #loadingIndicator {
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@if ($price == 0)
<script>
    async function onExamplePayButtonClick(event) {
        event.preventDefault(); // Prevent form submission

        const button = event.target;
        const originalButtonText = button.innerHTML;
        const loadingIndicator = document.createElement('div');
        loadingIndicator.id = 'loadingIndicator';
        loadingIndicator.innerHTML = `
            <div class="spinner-border spinner-border-sm text-light me-2" role="status">
                <span class="visually-hidden">Загрузка...</span>
            </div>
            <span>Обработка...</span>
        `;

        // Disable button and show loading indicator
        button.disabled = true;
        button.innerHTML = '';
        button.appendChild(loadingIndicator);

        try {
            const configurationDescriptions = getConfigurationDescriptions();

            // Send request to your server to handle the example smeta order creation
            const response = await fetch('/process-example-smeta-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    design_id: '{{ $id }}',
                    payment_provider: 'example',
                    selected_configuration: JSON.stringify(selectedOptionRefs),
                    configuration_descriptions: JSON.stringify(configurationDescriptions),
                    payment_amount: 0,
                    order_type: 'smeta',
                    price_type: document.getElementById('price_type').value,
                    @guest
                        logged_in: false,
                        name: document.getElementById('name').value,
                        email: document.getElementById('email').value,
                        phone: document.getElementById('phone').value,
                        password: document.getElementById('password').value,
                    @else
                        logged_in: true,
                        user_id: {{ auth()->user()->id }},
                    @endguest
                })
            });

            const data = await response.json();

            if (data.success) {
                // Redirect to the payment URL from your server response
                window.location.href = data.paymentUrl;
            } else {
                throw new Error('Failed to create order');
            }
        } catch (error) {
            console.error('Error:', error);
            // Handle error (e.g., show error message to user)
        } finally {
            // Restore button state
            button.disabled = false;
            button.innerHTML = originalButtonText;
        }
    }

    // Add event listener outside of the price condition
    const examplePayButton = document.getElementById('example_pay_button');
    if (examplePayButton) {
        examplePayButton.addEventListener('click', onExamplePayButtonClick);
    }

    // Prevent form submission
    const form = document.getElementById('{{ $modal_id }}_paymentForm');
    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
        });
    }
</script>
@endif

@if ($price > 0)

@endif



