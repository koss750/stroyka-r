@extends('layouts.fullwidth')

@section('content')
<div class="col-md-6" style="margin-top: 10%">
    <div class="authincation-content">
        <div class="row no-gutters">
            <div class="col-xl-12">
                <div class="auth-form">
                    <div class="text-center mb-3">
                        <a href="{{ url('index')}}"><img src="index_files/logo.png" width="80%" alt=""></a>
                    </div>
                    <h4 class="text-center mb-4 text-white">Регистрация аккаунта</h4>
                    <form action="{{ url('index')}}">
                        @csrf
                        <div class="form-group">
                            <label class="mb-1 text-white"><strong>Имя пользователя</strong></label>
                            <input type="text" class="form-control" placeholder="имя пользователя">
                        </div>
                        <div class="form-group">
                            <label class="mb-1 text-white"><strong>Email</strong></label>
                            <input type="email" class="form-control" placeholder="hello@example.com">
                        </div>
                        <div class="form-group">
                            <label class="mb-1 text-white"><strong>Пароль</strong></label>
                            <input type="password" class="form-control" value="Password">
                        </div>
                        <div class="form-group">
                            <label class="mb-1 text-white"><strong>Регион</strong></label>
                            <input type="password" class="form-control">
                        </div>
                        <div class="text-center mt-4">
                            <button type="submit" class="btn bg-white text-primary btn-block">Зарегистрироваться</button>
                        </div>
                    </form>
                    <div class="new-account mt-3">
                        <p class="text-white">Уже есть аккаунт? <a class="text-white" href="{{ url('page-login')}}">Войти</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
