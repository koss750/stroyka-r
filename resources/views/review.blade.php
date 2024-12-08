@extends('layouts.alternative')

@section('additional_head')
    <title>Успешная регистрация - Стройка.com</title>
    <meta name="description" content="Успешная регистрация - Стройка.com">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="alert alert-success mt-5" role="alert">
                <h4 class="alert-heading">Регистрация успешно завершена!</h4>
                <p>Ваша заявка на регистрацию успешно получена и будет рассмотрена в течение 72 часов.</p>
                <p>Пожалуйста, проверьте вашу электронную почту (включая папку "Спам") для подтверждения email-адреса.</p>
            </div>

            <div class="card">
                <div class="card-body">
                    <h2 class="text-center mb-4">{{ __('Вход') }}</h2>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input id="email" type="email" class="form-control no-text-transform @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Пароль') }}</label>
                            <div class="input-group">
                                <input id="password" type="password" class="form-control no-text-transform @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                <span class="input-group-text toggle-password" data-target="#password">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Запомнить меня') }}
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Войти') }}
                            </button>
                        </div>
                    </form>

                    @if (Route::has('password.request'))
                        <div class="text-center mt-3">
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Забыли пароль?') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection