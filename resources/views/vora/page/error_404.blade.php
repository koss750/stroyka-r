@extends('layouts.fullwidth')

@section('content')
<div class="col-md-6">
    <div class="form-input-content text-center error-page">
        <h1 class="error-text font-weight-bold">404</h1>
        <h4><i class="fa fa-exclamation-triangle text-warning"></i> Мы заблудилися!</h4>
        <p>Скорее всего эта страница еще не сделана или есть опечатка в адресе.. надо бы уточнить</p>
        <div>
            <a class="btn btn-primary" href="{{ url('index')}}">Вернуться обратно</a>
        </div>
    </div>
</div>
@endsection