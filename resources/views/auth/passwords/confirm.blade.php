@extends('layouts.alternative')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card mt-5">
                <div class="card-body">
                    <h2 class="text-center mb-4">{{ __('Подтверждение пароля') }}</h2>
                    <p class="text-center">{{ __('Пожалуйста, подтвердите свой пароль перед продолжением.') }}</p>
                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Пароль') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Подтвердить пароль') }}
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