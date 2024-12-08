@props([
    'id',
    'title',
    'image',
    'price',
    'type' => 'design'
])

@php
    if ($type === 'foundation') {
        $order_type = 'foundation';
        if ($price == 0) {
            $example = true;
            $modal_id = "exampleModal";
            $label = "Пример сметы на фундамент";
            $button_label = "Скачать пример сметы";
        } else {
            $example = false;
            $modal_id = "paymentModal";
            $button_label = "Оплатить";
            if (!isset($price)) {
                $price = 10;
            }
            $label = "Смета на фундамент - " . $price . "₽";
        }
    } else if ($type === 'design') {
        $order_type = 'design';
        if ($id == 1) {
            $example = true;
            $price = 0;
            $modal_id = "exampleModal";
            $label = "Пример сметы по выбранным параметрам";
            $button_label = "Скачать пример сметы";
        } else {
            $priceMaterial = $price['smeta_project'];
            $priceLabour = $price['smeta_project_labour'];
            $price = 1;
            $example = false;
            $modal_id = "paymentModal";
            $label = "Стоимость сметы";
            $button_label = "Оплатить";
        }
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
                            @guest
                                @if ($order_type != 'registration')
                                
                                <div id="registration-form">
                                    <div class="form-group">
                                        <label for="name">Имя:</label>
                                        <input type="text" class="form-control" id="name-guest-{{ $modal_id }}" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="email" class="form-control no-text-transform" id="email-guest-{{ $modal_id }}" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Телефон:</label>
                                        <input type="tel" class="form-control no-text-transform" id="phone-guest-{{ $modal_id }}" name="phone" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Создать пароль:</label>
                                        <input type="text" class="form-control no-text-transform" id="password-guest-{{ $modal_id }}" name="password" required>
                                    </div>
                                </div>
                                @endif
                            @endguest
                            @if ($price > 0 && $order_type == 'design')
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="price_type" id="price_type_material" value="smeta_project|{{ $priceMaterial }}" checked>
                                    <label class="form-check-label" for="price_type_material">
                                        Только материалы ({{ $priceMaterial }}₽)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="price_type" id="price_type_total" value="smeta_project_labour|{{ $priceLabour }}" {{ $price == 0 ? 'disabled' : '' }}>
                                    <label class="form-check-label" for="price_type_total">
                                        Материалы и работы ({{ $priceLabour }}₽)
                                    </label>
                                </div>
                            </div>
                            @elseif ($order_type == 'design' && $price == 0)
                                <input type="hidden" name="price_type" value="smeta_project|{{ $price }}">
                                <input type="hidden" name="amount" value="{{ $price }}">
                            @elseif ($order_type == 'foundation')
                                <input type="hidden" name="price_type" value="foundation|{{ $price }}">
                                <input type="hidden" name="amount" value="{{ $price }}">
                            @endif
                            <input type="hidden" name="description" value="Cмета на дом">

                            @if ($price == 0)
                                <button id="example_pay_button" type="button" class="btn btn-outline-light">{{ $button_label }}</button>
                            @else
                                <div id="tinkoffResponse"></div>
                                <button type="submit" class="btn btn-primary">{{ $button_label }}</button>
                            @endif
                        </form>
                        <div id="orderStatus" class="mt-3"></div>
                    </div>
                    <div class="col-md-7">
                        <!-- Right column content -->
                        @guest
                        <div id="registration-warning" class="alert alert-info">
                            <p class="mb-0">Для доступа к нашим услугам необходимо зарегистрирваться, заполнив свои данные на этой странице.</p>
                        </div>
                        <div id="registration-warning" class="alert alert-danger">
                            <p class="mb-0">Пожалуйста подтвердите ваш email после заполнения данных перейдя по ссылке в письме.</p>
                        </div>
                        @endguest
                        @if ($price > 0)
                            <h5>Проект {{ $title }}</h5>
                            <img src="{{ $image }}" alt="{{ $title }}" class="img-fluid mb-3">
                        @endif
                        @if ($type === 'design')
                            <h5>Выбранные параметры:</h5>
                            <ul class="list-unstyled">
                                <li><strong>Фундамент:</strong> <span id="{{ $modal_id }}_foundation"></span></li>
                                <li><strong>Домокомплект:</strong> <span id="{{ $modal_id }}_dd"></span></li>
                                <li><strong>Кровля:</strong> <span id="{{ $modal_id }}_roof"></span></li>
                            </ul>
                        @endif
                        <p class="mt-3">После создания, смета будет доступна для скачивания 30 дней через личный кабинет.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('further_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Only include configuration functions for design orders
        @if($type === 'design')
        function getConfigurationDescriptions() {
            function removePrefix(text, prefix) {
                return text.startsWith(prefix) ? text.slice(prefix.length).trim() : text;
            }

            const descriptions = {
                foundation: removePrefix(document.getElementById('title_label_foundation')?.textContent || '', 'Фундамент -'),
                dd: removePrefix(document.getElementById('title_label_dd')?.textContent || '', 'Домокомплект -'),
                roof: removePrefix(document.getElementById('title_label_roof')?.textContent || '', 'Кровля -')
            };

            return descriptions;
        }

        function updateModalText() {
            const descriptions = getConfigurationDescriptions();
            document.getElementById('{{ $modal_id }}_foundation').textContent = descriptions.foundation;
            document.getElementById('{{ $modal_id }}_dd').textContent = descriptions.dd;
            document.getElementById('{{ $modal_id }}_roof').textContent = descriptions.roof;
        }
        @endif

        // Initialize form handlers when modal is shown
        document.addEventListener('shown.bs.modal', function(event) {
            if (event.target.id !== '{{ $modal_id }}') return;
            
            @if($type === 'design')
            // Update configuration text only for design orders
            updateModalText();
            @endif
            
            const form = document.getElementById('{{ $modal_id }}_paymentForm');
            if (!form) return;

            // Remove any existing listeners
            const newForm = form.cloneNode(true);
            form.parentNode.replaceChild(newForm, form);

            // Add appropriate handler based on price
            if ({{ $price }} == 0) {
                const exampleButton = newForm.querySelector('#example_pay_button');
                if (exampleButton) {
                    exampleButton.addEventListener('click', handleExampleSubmit);
                }
            } else {
                newForm.addEventListener('submit', handlePaymentSubmit);
            }
        });

        // Common order submission function
        async function submitOrder(provider) {
            event.preventDefault();
            
            const form = document.getElementById('{{ $modal_id }}_paymentForm');
            const formData = new FormData(form);
            
            // First handle guest registration if needed
            if (document.getElementById('name-guest-{{ $modal_id }}')) {
                const name = document.getElementById('name-guest-{{ $modal_id }}').value.trim();
                const email = document.getElementById('email-guest-{{ $modal_id }}').value.trim();
                const phone = document.getElementById('phone-guest-{{ $modal_id }}').value.trim();
                const password = document.getElementById('password-guest-{{ $modal_id }}').value.trim();
                
                console.log('Form values:', { name, email, phone, password }); // Better debugging
                
                if (!name || !email || !phone) {
                    alert('Имя, email и телефон должны быть заполнены');
                    return;
                }
                
                if (password.length < 8) {
                    alert('Пароль должен быть длиннее 8 символов');
                    return;
                }
                if (!/^[a-zA-Z0-9]+$/.test(password)) {
                    alert('Пароль должен содержать только латинские буквы и цифры');
                    return;
                }
            }

            const button = event.target;
            showLoading(button);

            // Then handle price type
            let selectedPrice, selectedPriceAmount;
            const priceTypeValue = formData.get('price_type');
            
            if (priceTypeValue) {
                const priceType = priceTypeValue.split('|');
                selectedPrice = priceType[0];
                selectedPriceAmount = priceType[1];
            } else {
                alert('Не выбран тип цены');
                return;
            }

            let orderData = {
                payment_provider: provider,
                payment_amount: selectedPriceAmount,
                order_type: '{{ $order_type }}',
                price_type: selectedPrice,
                @guest
                    logged_in: false,
                    name: formData.get('name'),
                    email: formData.get('email'),
                    phone: formData.get('phone'),
                    password: formData.get('password'),
                @else
                    logged_in: true,
                    user_id: {{ auth()->user()->id }},
                @endguest
            };

            // Add type-specific data
            if ('{{ $type }}' === 'foundation') {
                try {
                    if (typeof ym !== 'undefined') {
                        ym(97430601, 'reachGoal', 'foundation_smeta_attempt');
                    } else {
                        console.warn('Yandex Metrika not initialized');
                    }
                } catch (error) {
                    console.error('Yandex Metrika error:', error);
                }
                const foundationForm = document.getElementById('stripFoundationForm');
                const inputs = foundationForm.querySelectorAll('[data-excel-cell]');
                orderData.foundation_data = {};

                inputs.forEach(input => {
                    const cellIndex = input.getAttribute('data-excel-cell');
                    let cellValue;

                    if (input.tagName.toLowerCase() === 'select') {
                        // For select elements, get the selected option's value
                        cellValue = input.value || '';
                    } else {
                        // For other input types, use existing logic
                        if ({{ $price }} === 0) {
                            cellValue = input.placeholder || '';
                        } else {
                            cellValue = input.value || '';
                        }
                    }
                    
                    orderData.foundation_id = '{{ $id }}';
                    orderData.foundation_data[cellIndex] = cellValue;
                });
            } else {
                // Design order specific data
                try {
                    if (typeof ym !== 'undefined') {
                        ym(97430601, 'reachGoal', 'design_smeta_attempt');
                    } else {
                        console.warn('Yandex Metrika not initialized');
                    }
                } catch (error) {
                    console.error('Yandex Metrika error:', error);
                }
                orderData.design_id = '{{ $id }}';
                orderData.selected_configuration = JSON.stringify(selectedOptionRefs);
                orderData.configuration_descriptions = JSON.stringify(getConfigurationDescriptions());
            }

            // Determine the correct endpoint based on type and provider
            const endpoint = '/api/tinkoff/init';
            
            console.log('Submitting order to:', endpoint, orderData);

            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(orderData)
            });
            return response.json();
        }
        let name, email, phone, password;

        
        // Handler for example (free) submissions
        async function handleExampleSubmit(event) {
            event.preventDefault(); // Move this to the top
            try {
                if (typeof ym !== 'undefined') {
                    ym(97430601, 'reachGoal', 'free_smeta');
                } else {
                    console.warn('Yandex Metrika not initialized');
                }
            } catch (error) {
                console.error('Yandex Metrika error:', error);
            }
            if (document.getElementById('name-guest-{{ $modal_id }}')) {
                const name = document.getElementById('name-guest-{{ $modal_id }}').value.trim();
                const email = document.getElementById('email-guest-{{ $modal_id }}').value.trim();
                const phone = document.getElementById('phone-guest-{{ $modal_id }}').value.trim();
                const password = document.getElementById('password-guest-{{ $modal_id }}').value.trim();
                
                console.log('Form values:', { name, email, phone, password }); // Better debugging
                
                if (!name || !email || !phone) {
                    alert('Имя, email и телефон должны быть заполнены');
                    return;
                }
                
                if (password.length < 8) {
                    alert('Пароль должен быть длиннее 8 символов');
                    return;
                }
                if (!/^[a-zA-Z0-9]+$/.test(password)) {
                    alert('Пароль должен содержать только латинские буквы и цифры');
                    return;
                }
            }
            const button = event.target;
            showLoading(button);
            
            

            try {
                const response = await submitOrder('example');
                if (response.success) {
                    window.location.href = response.paymentUrl;
                } else {
                    throw new Error('Failed to create order');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Произошла ошибка при обработке заказа');
            } finally {
                hideLoading(button);
            }
        }

        // Handler for paid submissions
        async function handlePaymentSubmit(event) {
            event.preventDefault();
            const button = event.submitter;
            showLoading(button);

            try {
                const response = await submitOrder('tinkoff');
                console.log(response);
                window.location.href = response.paymentUrl;
            } catch (error) {
                console.error('Payment error:', error);
                alert('Ошибка платежа');
            } finally {
                hideLoading(button);
            }
        }

        // UI helper functions
        function showLoading(button) {
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = `
                <div class="spinner-border spinner-border-sm text-light me-2" role="status">
                    <span class="visually-hidden">Загрузка...</span>
                </div>
                <span>Обработка...</span>
            `;
            button.setAttribute('data-original-text', originalText);
        }

        function hideLoading(button) {
            button.disabled = false;
            button.innerHTML = button.getAttribute('data-original-text');
        }
    });
</script>
@endpush

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

    button[type="submit"]:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>