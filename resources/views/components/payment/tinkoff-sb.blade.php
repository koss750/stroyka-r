@props(['id', 'price', 'modalId'])

@push('further_scripts')
<script src="https://securepay.tinkoff.ru/html/payForm/js/tinkoff_v2.js"></script>
@endpush

<form id="tinkoffForm_{{ $id }}" class="payform-tbank" name="payform-tbank" action="javascript:void(0);">
    <input type="hidden" name="amount" value="{{ $price }}">
    <input type="hidden" name="orderId" value="{{ $id }}">
    <input type="hidden" name="description" value="смета на дом">
    @if (!auth()->check())
        <input type="text" name="email">
        <input type="text" name="phone">
    @else
        <input type="hidden" name="email" value="{{ auth()->user()->email }}">
        <input type="hidden" name="phone" value="{{ auth()->user()->phone }}">
    @endif
    <input class="payform-tbank-row payform-tbank-btn" id="tinkoffSubmit_{{ $id }}" type="submit" value="Оплатить">
</form>
<div id="tinkoffResponse"></div>

@push('further_scripts')
<script>
console.log('Script tag started for form ID: tinkoffForm_{{ $id }}');

try {
    // Listen for modal show event
    document.addEventListener('shown.bs.modal', function (event) {
        console.log('Modal shown, target:', event.target.id);
        if (event.target.id === '{{ $modalId }}') {
            console.log('Initializing form for modal:', event.target.id);
            initTinkoffForm();
        }
    });

    function initTinkoffForm() {
        const formId = 'paymentModal_paymentForm';
        console.log('Looking for form with ID:', formId);
        const form = document.getElementById(formId);
        
        if (!form) {
            console.error('Tinkoff form not found:', formId);
            return;
        }

        console.log('Form found, attaching listener');
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            console.log('Form submitted');
            
            try {
                const formData = {
                    amount: form.querySelector('[name="amount"]').value,
                    orderId: form.querySelector('[name="orderId"]').value,
                    description: form.querySelector('[name="description"]').value,
                    email: form.querySelector('[name="email"]').value,
                    phone: form.querySelector('[name="phone"]').value
                };
                
                console.log('Sending data:', formData);
                
                const response = await fetch('/api/tinkoff/init', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify(formData)
                });

                const data = await response.json();
                console.log('Received response:', data);
                
                if (data.paymentUrl) {
                    window.location.href = data.paymentUrl;
                } else {
                    console.error('Payment initialization failed:', data);
                    alert('Ошибка инициализации платежа');
                }
            } catch (error) {
                console.error('Payment error:', error);
                alert('Ошибка платежа');
            }
        });
    }
} catch (error) {
    console.error('Script error:', error);
}
</script>
@endpush