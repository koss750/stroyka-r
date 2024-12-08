@extends('layouts.alternative-email')

@section('content')
<div class="container mt-5" style="max-width:500px;">
    <div class="card">
        <div class="card-header">
            <h2 class="text-center">Регистрация юридического лица</h2>
        </div>
        <div class="card-body">
            <div class="text-center mb-4">
                <h3>Портал "Стройка.com"</h3>
                <p class="no-margin">info@стройка.com</p>
            </div>

            <div class="row mb-3">
                <div class="col-12">
                    <p>Добрый день!</p>
                    <p>Благодарим вас за регистрацию вашей компании на нашем портале. Мы получили вашу заявку и начнем ее обработку в ближайшее время.</p>
                    <p>Пожалуйста, ожидайте ответа в течение 24-72 часов. Мы свяжемся с вами по указанным контактным данным.</p>
                </div>
            </div>

            <hr>

            <div class="row mb-3">
                <div class="col-12">
                    <p>Если у вас есть какие-либо вопросы, пожалуйста, свяжитесь с нашей службой поддержки.</p>
                    <p>С уважением,</p>
                    <p>Команда Стройка.com</p>
                </div>
            </div>

            <div class="text-center mt-4">
                <p>Спасибо за ваш интерес к нашему порталу!</p>
            </div>
        </div>
    </div>
</div>
@endsection