@props(['id', 'price', 'modalId'])


<div id="button_container"></div>

@push('further_head')
<script src="https://pay.yandex.ru/sdk/v1/pay.js" onload="onYaPayLoad()" async></script>
@endpush

@push('further_scripts')
<script>
    function onYaPayLoad() {
        const YaPay = window.YaPay;

        // Данные платежа
        const paymentData = {
            // Для отладки нужно явно указать `SANDBOX` окружение,
            // для продакшена параметр можно убрать или указать `PRODUCTION`
            env: YaPay.PaymentEnv.Sandbox,

            // Версия 4 указывает на тип оплаты сервисом Яндекс Пэй
            // Пользователь производит оплату на форме Яндекс Пэй,
            // и мерчанту возвращается только результат проведения оплаты
            version: 4,

            // Код валюты в которой будете принимать платежи
            currencyCode: YaPay.CurrencyCode.Rub,

            // Идентификатор продавца, который получают при регистрации в Яндекс Пэй
            merchantId: 'cc08ce31-8375-439d-86f1-1201533f53e7',

            // Сумма к оплате
            // Сумма которая будет отображена на форме зависит от суммы переданной от бэкенда
            // Эта сумма влияет на отображение доступности Сплита
            totalAmount: {{ $price }},

            // Доступные для использования методы оплаты
            // Доступные на форме способы оплаты также зависят от информации переданной от бэкенда
            // Данные передаваемые тут влияют на внешний вид кнопки или виджета
            availablePaymentMethods: ['CARD'],
        };

        async function onPayButtonClick() {
            try {
                const configurationDescriptions = getConfigurationDescriptions();

                url = '/process-project-smeta-order';
                // Send request to your server to handle the Yandex Pay order creation
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        design_id: '{{ $id }}',
                        payment_provider: 'yandex',
                        selected_configuration: JSON.stringify(selectedOptionRefs),
                        configuration_descriptions: JSON.stringify(configurationDescriptions),
                        payment_amount: {{ $price }},
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
                    // Return the payment URL from your server response
                    return data.paymentUrl;
                } else {
                    throw new Error('Failed to create order');
                }
            } catch (error) {
                console.error('Error creating order:', error);
                throw error;
            }
        }

        // Обработчик на ошибки при открытии формы оплаты
        function onFormOpenError(reason) {
            // Выводим информацию о недоступности оплаты в данный момент
            // и предлагаем пользователю другой способ оплаты.
            console.error(`Payment error — ${reason}`);
        }

        // Modify the createSession callback
        YaPay.createSession(paymentData, {
            onPayButtonClick: onPayButtonClick,
            onFormOpenError: onFormOpenError,
        })
            .then(function (paymentSession) {
                // Mount the Yandex Pay button
                paymentSession.mountButton(document.querySelector('#button_container'), {
                    type: YaPay.ButtonType.Pay,
                    theme: YaPay.ButtonTheme.Black,
                    width: YaPay.ButtonWidth.Max,
                });
                console.log('Payment session created successfully');
                console.log(paymentSession);
            })
            .catch(function (err) {
                console.error('Failed to create payment session:', err);
            });
    }
</script>
@endpush