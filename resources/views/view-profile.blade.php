@extends('layouts.alternative')
@section('additional_head')
<title>{{ $supplier->company_name }}</title>
<meta name="description" content="Все данные о {{ $supplier->company_name }}">
@endsection
@section('content')
<div class="container-fluid">
    @include('partials.tab-navigator', ['items' => [
        ['url' => '/my-orders', 'label' => 'Заказы'],
        ['url' => '/suppliers', 'label' => 'Строители'],
        ['url' => '/messages', 'label' => 'Мои переписки'],
        ['url' => '/profile', 'label' => 'Мои данные'],
    ]])

    <div class="row">
        <div class="col-xl-5">
            <div class="card">
                <div class="card-body">
                    
                    <div class="profile-info mt-5">
                                <h4 class="text-primary mb-4">Контактная информация</h4>
                                <div class="row mb-3">
                                    <div class="col-sm-4 col-6">
                                        <h5 class="f-w-500">Email <span class="pull-right">:</span></h5>
                                    </div>
                                    <div class="col-sm-8 col-6"><span>{{ $supplier->email }}</span></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 col-6">
                                        <h5 class="f-w-500">Основной телефон <span class="pull-right">:</span></h5>
                                    </div> 
                                    <div class="col-sm-8 col-6"><span>{{ $supplier->phone }}</span></div>
                                </div>
                                @if($supplier->additional_phone)
                                <div class="row mb-3">
                                    <div class="col-sm-4 col-6">
                                        <h5 class="f-w-500">Дополнительный телефон <span class="pull-right">:</span></h5>
                                    </div>
                                    <div class="col-sm-8 col-6"><span>{{ $supplier->additional_phone }}</span></div>
                                </div>
                                @endif
                                <div class="row mb-3">
                                    <div class="col-sm-4 col-6">
                                        <h5 class="f-w-500">Юридический адрес <span class="pull-right">:</span></h5>
                                    </div>
                                    <div class="col-sm-8 col-6"><span>{{ $supplier->legal_address }}</span></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 col-6">
                                        <h5 class="f-w-500">Фактический адрес <span class="pull-right">:</span></h5>
                                    </div>
                                    <div class="col-sm-8 col-6"><span>{{ $supplier->physical_address }}</span></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 col-6">
                                        <h5 class="f-w-500">ИНН <span class="pull-right">:</span></h5>
                                    </div>
                                    <div class="col-sm-8 col-6"><span>{{ $supplier->inn }}</span></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 col-6">
                                        <h5 class="f-w-500">ОГРН <span class="pull-right">:</span></h5>
                                    </div>
                                    <div class="col-sm-8 col-6"><span>{{ $supplier->ogrn }}</span></div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-4 col-6">
                                        <h5 class="f-w-500">Основной регион <span class="pull-right">:</span></h5>
                                    </div>
                                    <div class="col-sm-8 col-6"><span>Москва</span></div>
                                </div>
                                
                                <div class="row mb-3">
                                <div class="col-sm-4 col-6">
                                        <h5 class="f-w-500" style="margin-bottom: 0;">Регионы <span class="pull-right">:</span></h5>
                                        <span class="small">оказания услуг</span>
                                    </div>
                                    <div class="col-sm-8 col-6">
                                        @php
                                            $regions = $supplier->regions->filter(function ($region) use ($supplier) {
                                                if($region == null) {
                                                    return 'Москва';
                                                } else {
                                                    return $region->id !== optional($supplier->main_region)->id;
                                                }
                                            })->pluck('name')->implode(', ');

                                            
                                            $isLong = strlen($regions) > 66; // Adjust the character limit as needed
                                        @endphp
                                        
                                        <span class="{{ $isLong ? 'regions-preview' : '' }}">
                                            {{ $isLong ? Str::limit($regions, 66) : $regions }}
                                        </span>
                                        
                                        @if ($isLong)
                                            <span class="regions-full d-none">{{ $regions }}</span>
                                            <a class="regions-toggle" href="#" role="button">
                                                <br>
                                                <span class="show-more">Показать все</span>
                                                <span class="show-less d-none">Скрыть</span>
                                            </a>
                                        @endif
                                        
                                        @if (!$regions)
                                            <span>Данный пользователь не указал регионы</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                </div>
            </div>
        </div>
        <div class="col-xl-7">
            <div class="card">
                <div class="card-body">
                    <div class="profile-tab">
                        <div class="custom-tab-1">
                            <ul class="nav nav-tabs left-aligned-tabs">
                                <li class="nav-item"><a href="#about-me" data-bs-toggle="tab" class="nav-link active show">О компании</a></li>
                                @if(1==0 && $currentUser->id === $supplier->user_id)
                                    <li class="nav-item"><a href="#profile-settings" data-bs-toggle="tab" class="nav-link">Настройки</a></li>
                                @endif
                            </ul>
                            <div class="tab-content">
                            <div class="profile-statistics">
                                <div class="text-center">
                                    <div class="row">
                                        <div class="col-xl-2 col-sm-12 text-center">
                                            <div class="profile-photo">
                                                <img src="{{ $supplier->profile_picture_url ?? asset('images/profile/default.png') }}" class="img-fluid rounded-circle" alt="{{ $supplier->company_name }}">
                                            </div>
                                        </div>
                                        <div class="col-xl-8 col-sm-12 text-center">
                                            <h3 class="mt-3 mb-1 text-left">{{ $supplier->company_name }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <div id="about-me" class="tab-pane fade active show">
                                    <div class="profile-about-me">
                                        <div class="pt-4 border-bottom-1 pb-3">
                                            <h4 class="text-primary">О компании</h4>
                                            <div class="company-description">
                                                <p>{{ Str::limit($supplier->message, 700) }}</p>
                                                @if(strlen($supplier->message) > 700)
                                                    <div class="collapse" id="readMore">
                                                        <p>{{ substr($supplier->message, 700) }}</p>
                                                    </div>
                                                    <a class="read-more-link" data-bs-toggle="collapse" href="#readMore" role="button" aria-expanded="false" aria-controls="readMore">
                                                        <span class="read-more">Читать дальше...</span>
                                                        <span class="read-less d-none">Скрыть</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="profile-personal-info mt-4" style="display: none;">
                                        <h4 class="text-primary mb-4">Дополнительная информация</h4>
                                        
                                        <div class="row mb-2">
                                            <div class="col-3">
                                                <h5 class="f-w-500">Что то сюда надо может быть <span class="pull-right">:</span></h5>
                                            </div>
                                            <div class="col-9"><span>Текст на всякий случай... </span></div>
                                        </div>
                                    </div>
                                </div>
                                @if(1==0 && $currentUser->id === $supplier->user_id)
                                    <div id="profile-settings" class="tab-pane fade">
                                        <div class="pt-3">
                                            <div class="settings-form">
                                                <h4 class="text-primary">Настройки профиля</h4>
                                                <form action="" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <!-- Add form fields for updating supplier information -->
                                                    <button class="btn btn-primary" type="submit">Сохранить изменения</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional_scripts')
<script>
    $(document).ready(function() {
        $('.read-more-link').click(function() {
            $(this).find('.read-more, .read-less').toggleClass('d-none');
        });

        $('.regions-toggle').click(function(e) {
            e.preventDefault();
            $(this).siblings('.regions-preview, .regions-full').toggleClass('d-none');
            $(this).find('.show-more, .show-less').toggleClass('d-none');
        });
    });
</script>
@endsection
<style>
    .left-aligned-tabs {
        justify-content: flex-start !important;
    }
</style>