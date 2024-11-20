@extends('layouts.alternative')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card mt-5">
                <div class="card-body">
                    <h2 class="text-center mb-4">{{ __('Сброс пароля') }}</h2>
                    <div id="status-message" class="alert" style="display: none;"></div>
                    
                    <form id="reset-form" method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus style="text-transform: none;">
                            <span class="invalid-feedback" role="alert" id="email-error"></span>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" id="submit-btn">
                                {{ __('Отправить ссылку для сброса') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('reset-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submit-btn');
    const statusMessage = document.getElementById('status-message');
    const emailError = document.getElementById('email-error');
    
    // Reset previous error states
    document.getElementById('email').classList.remove('is-invalid');
    emailError.textContent = '';
    statusMessage.style.display = 'none';
    
    // Disable submit button and show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Отправка...';

    fetch('{{ route("password.email") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            email: document.getElementById('email').value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            statusMessage.className = 'alert alert-success';
            statusMessage.textContent = 'Ссылка для сброса пароля отправлена на ваш email';
            statusMessage.style.display = 'block';
            
            // Redirect to login page after 3 seconds
            setTimeout(() => {
                window.location.href = '/login';
            }, 2000);
        } else {
            throw new Error(data.message || 'Произошла ошибка');
        }
    })
    .catch(error => {
        if (error.response && error.response.status === 422) {
            // Validation errors
            const errors = error.response.data.errors;
            if (errors.email) {
                document.getElementById('email').classList.add('is-invalid');
                emailError.textContent = errors.email[0];
            }
        } else {
            // General error
            statusMessage.className = 'alert alert-danger';
            statusMessage.textContent = error.message || 'Произошла ошибка при отправке запроса';
            statusMessage.style.display = 'block';
        }
    })
    .finally(() => {
        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.textContent = 'Отправить ссылку для сброса';
    });
});
</script>
@endsection