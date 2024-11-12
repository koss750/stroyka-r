<div id="exampleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content modal-content-payment">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Пример сметы по выбранным параметрам</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <form id="examplePaymentForm">
                            @guest
                                <div class="form-group">
                                    <label for="name">Имя:</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Телефон:</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Создать пароль:</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            @endguest
                            <input type="hidden" name="design_id" value="1">
                            <input type="hidden" name="order_type" value="smeta">

                            <button type="submit" class="btn btn-outline-light">Получить пример сметы</button>
                        </form>
                        <div id="orderStatus" class="mt-3"></div>
                    </div>
                    <div class="col-md-7">
                        <h5>Проект для примера сметы</h5>
                        <h5>Выбранные параметры:</h5>
                        <ul class="list-unstyled">
                            <li><strong>Фундамент:</strong> <span id="modal_foundation"></span></li>
                            <li><strong>Домокомплект:</strong> <span id="modal_dd"></span></li>
                            <li><strong>Кровля:</strong> <span id="modal_roof"></span></li>
                        </ul>
                        <p class="mt-3">После генерации пример сметы, вы сможете скачать её в своем личном кабинете.</p>
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let exampleConfigurationDescriptions = {};
    var exampleModal = document.getElementById('exampleModal');

    function updateExampleConfiguration() {
        function removeExamplePrefix(text, prefix) {
            return text.startsWith(prefix) ? text.slice(prefix.length).trim() : text;
        }

        function updateExampleModalField(optionId, modalId, prefix) {
            var optionElement = document.querySelector(`#${optionId} .form-check-input:checked + label`);
            var modalElement = document.getElementById(modalId);
            
            console.log(`Updating ${modalId}:`, { optionElement, modalElement });
            
            if (optionElement && modalElement) {
                var text = optionElement.textContent;
                var description = removeExamplePrefix(text, prefix);
                modalElement.textContent = description;
                return description;
            } else {
                console.warn(`Failed to update ${modalId}. Option: ${!!optionElement}, Modal: ${!!modalElement}`);
            }
            return '';
        }

        exampleConfigurationDescriptions = {
            foundation: updateExampleModalField('foundation_options', 'modal_foundation', 'Фундамент -'),
            dd: updateExampleModalField('dd_options', 'modal_dd', 'Домокомплект -'),
            roof: updateExampleModalField('roof_options', 'modal_roof', 'Кровля -')
        };

        console.log('Final exampleConfigurationDescriptions:', exampleConfigurationDescriptions);
    }

    // Update configuration when the page loads
    updateExampleConfiguration();

    // Update configuration when the modal is shown
    if (exampleModal) {
        exampleModal.addEventListener('show.bs.modal', updateExampleConfiguration);
    }

    document.getElementById('examplePaymentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        processExampleOrder();
    });

    function processExampleOrder() {
        var formColumn = document.querySelector('#exampleModal .col-md-7');
        var isLoggedIn = @auth true @else false @endauth;
        var formData = new FormData(document.getElementById('examplePaymentForm'));

        // Show loading spinner
        formColumn.innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Генерация сметы...</span>
                </div>
                <p class="mt-3">Генерация сметы...</p>
            </div>
        `;

        // Function to register order
        function registerExampleOrder() {
            const designId = '1';  // Use the id prop directly
            const paymentAmount = 0; // Assuming the payment amount is free
            return fetch('/register-order-smeta', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    design_id: designId,
                    selected_configuration: JSON.stringify(selectedOptionRefs),
                    configuration_descriptions: JSON.stringify(exampleConfigurationDescriptions),
                    payment_amount: paymentAmount,
                    order_type: 's_example'
                })
            }).then(response => response.json());
        }

        // Process payment and register order
        (isLoggedIn ? Promise.resolve() : fetch('/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(Object.fromEntries(formData))
        }).then(response => response.json()))
        .then(() => registerExampleOrder())
        .then(data => {
            if (isLoggedIn) {
                formColumn.innerHTML = `
                    <div class="text-center">
                        <h4 class="mb-4">Заказ подтвержден</h4>
                        <p>Спасибо за ваш заказ! Номер заказа: ${data.orderId}</p>
                        <a href="/my-orders" class="btn btn-outline-light">Перейти к заказам</a>
                    </div>
                `;
            } else {
                formColumn.innerHTML = `
                    <div class="text-center">
                        <h4 class="mb-4">Заказ подтвержден</h4>
                        <p>Спасибо за ваш заказ! Номер заказа: ${data.orderId}</p>
                        <p>Пожалуйста, проверьте вашу электронную почту и перейдите по ссылке для подтверждения.</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            formColumn.innerHTML = `
                <div class="text-center">
                    <h4 class="mb-4">Ошибка</h4>
                    <p>Произошла ошибка при обработке вашего заказа. Пожалуйста, попробуйте еще раз позже.</p>
                </div>
            `;
        });
    }
});
</script>