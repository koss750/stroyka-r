@extends('layouts.alternative')

@section('content')
<div class="container mt-5" style="max-width:500px;">
    <div class="card">
        <div class="card-header">
            <h2 class="text-center">Оплата заказа</h2>
        </div>
        <div class="card-body">
            <div class="text-center mb-4">
                <h3>Портал "Стройка.com"</h3>
                <p class="no-margin">info@стройка.com</p>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <strong>КАССОВЫЙ ЧЕК №:</strong> {{ $project['payment_reference'] }}
                </div>
                <div class="col-6 text-right">
                    <strong>Дата:</strong> {{ $time->format('d.m.y H:i') }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <strong>СМЕНА:</strong> 1001
                </div>
                <div class="col-6 text-right">
                    <strong>КАССИР:</strong> АДМИНИСТРАТОР
                </div>
            </div>

            <hr>

            <h4 class="text-center mb-3">ПРИХОД</h4>

            <div class="row mb-3">
                <div class="col-8">
                    <strong>Заказ {{ $project['human_ref'] }}</strong>
                    <br>
                    <small><span class="text-muted">{{ $type_description }}</span></small>
                </div>
                <div class="col-4 text-right">
                    1 x {{ $project['payment_amount'] }} руб.
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-8">
                    <strong>НДС</strong>
                </div>
                <div class="col-4 text-right">
                    0 руб.
                </div>
            </div>

            <hr>

            <div class="row mb-3">
                <div class="col-8">
                    <strong>ИТОГО:</strong>
                </div>
                <div class="col-4 text-right">
                    <strong>{{ $project['payment_amount'] }} руб.</strong>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-8">
                    <strong>БЕЗНАЛИЧНЫМИ:</strong>
                </div>
                <div class="col-4 text-right">
                    {{ $project['payment_amount'] }} руб.
                </div>
            </div>

            <hr>

            <p class="no-margin"><strong>Признак способа расчета:</strong> АВАНС</p>
            <p class="no-margin"><strong>Признак предмета расчета:</strong> УСЛУГА</p>
            <p class="no-margin"><strong>ККТ для Интернет:</strong> Да</p>
            <p class="no-margin"><strong>ЭЛ.АДР.ОТПРАВИТЕЛЯ:</strong> info@стройка.com</p>
            <p class="no-margin"><strong>САЙТ ФНС:</strong> www.nalog.gov.ru</p>
            <p class="no-margin"><strong>ЭЛ.АДР.ПОКУПАТЕЛЯ:</strong> {{ $user_email }}</p>
            

            <div class="text-center mt-4">
                <p>Спасибо за покупку!</p>
            </div>
        </div>
    </div>
</div>
@endsection