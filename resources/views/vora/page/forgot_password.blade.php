@extends('layouts.fullwidth')

@section('content')
<div class="col-md-6">
    <div class="authincation-content">
        <div class="row no-gutters">
            <div class="col-xl-12">
                <div class="auth-form">
                    <div class="text-center mb-3">
                        <a href="{{ url('index')}}"><img src="{{ asset('images/logo-full.png')}}" alt=""></a>
                    </div>
                    <h4 class="text-center mb-4 text-white">Забыли Пароль</h4>
                    <form action="{{ url('index')}}">
                        @csrf
                        <div class="form-group">
                            <label class="text-white"><strong>Электронная почта</strong></label>
                            <input type="email" class="form-control" value="hello@example.com">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn bg-white text-primary btn-block">ОТПРАВИТЬ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
