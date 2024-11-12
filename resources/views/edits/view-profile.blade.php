@extends('layouts.alternative')

@section('content')
<div class="container-fluid">
    @include('partials.tab-navigator', ['items' => [
        ['url' => '/my-orders', 'label' => __('captions.orders')],
        ['url' => '/suppliers', 'label' => __('captions.performers')],
        ['url' => '/messages', 'label' => __('captions.my_conversations')],
        ['url' => '/profile', 'label' => __('captions.my_data')],
    ]])

    <div class="row">
        <div class="col-xl-5">
            <div class="card">
                <div class="card-body">
                    <div class="profile-statistics">
                        <div class="text-center">
                            <div class="row">
                                <div class="col-4">
                                    <div class="profile-photo">
                                        <img src="{{ $supplier->profile_picture_url ?? asset('images/profile/default.png') }}" class="img-fluid rounded-circle" alt="{{ $supplier->company_name }}">
                                    </div>
                                </div>
                                <div class="col-8">
                                    <h3 class="mt-3 mb-1">{{ $supplier->company_name }}</h3>
                                    <p>{{ $supplier->type == 'contractor' ? __('captions.contractor') : __('captions.supplier') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="profile-info mt-5">
                        <h4 class="text-primary mb-4">{{ __('captions.contact_information') }}</h4>
                        <div class="row mb-3">
                            <div class="col-sm-4 col-6">
                                <h5 class="f-w-500">{{ __('captions.email') }} <span class="pull-right">:</span></h5>
                            </div>
                            <div class="col-sm-8 col-6"><span>{{ $supplier->email }}</span></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 col-6">
                                <h5 class="f-w-500">{{ __('captions.main_phone') }} <span class="pull-right">:</span></h5>
                            </div> 
                            <div class="col-sm-8 col-6"><span>{{ $supplier->phone_1 }}</span></div>
                        </div>
                        @if($supplier->phone_2)
                        <div class="row mb-3">
                            <div class="col-sm-4 col-6">
                                <h5 class="f-w-500">{{ __('captions.additional_phone') }} <span class="pull-right">:</span></h5>
                            </div>
                            <div class="col-sm-8 col-6"><span>{{ $supplier->phone_2 }}</span></div>
                        </div>
                        @endif
                        <div class="row mb-3">
                            <div class="col-sm-4 col-6">
                                <h5 class="f-w-500">{{ __('captions.address') }} <span class="pull-right">:</span></h5>
                            </div>
                            <div class="col-sm-8 col-6"><span>{{ $supplier->address }}</span></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 col-6">
                                <h5 class="f-w-500">{{ __('captions.inn') }} <span class="pull-right">:</span></h5>
                            </div>
                            <div class="col-sm-8 col-6"><span>{{ $supplier->inn }}</span></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 col-6">
                                <h5 class="f-w-500">{{ __('captions.organization_type') }} <span class="pull-right">:</span></h5>
                            </div>
                            <div class="col-sm-8 col-6"><span>{{ $supplier->type_of_organisation }}</span></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 col-6">
                                <h5 class="f-w-500">{{ __('captions.main_region') }} <span class="pull-right">:</span></h5>
                            </div>
                            <div class="col-sm-8 col-6"><span>{{ $supplier->main_region->name ?? __('captions.moscow') }}</span></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4 col-6">
                                <h5 class="f-w-500">{{ __('captions.working_regions') }} <span class="pull-right">:</span></h5>
                            </div>
                            <div class="col-sm-8 col-6"><span>{{ $supplier->regions->pluck('name')->implode(', ') ?: __('captions.no_regions_specified') }}</span></div>
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
                                <li class="nav-item"><a href="#about-me" data-bs-toggle="tab" class="nav-link active show">{{ __('captions.about_company') }}</a></li>
                                @if($currentUser && $currentUser->id === $supplier->user_id)
                                    <li class="nav-item"><a href="#profile-settings" data-bs-toggle="tab" class="nav-link">{{ __('captions.settings') }}</a></li>
                                @endif
                            </ul>
                            <div class="tab-content">
                                <div id="about-me" class="tab-pane fade active show">
                                    <div class="profile-about-me">
                                        <div class="pt-4 border-bottom-1 pb-3">
                                            <h4 class="text-primary">{{ __('captions.about_company') }}</h4>
                                            <p>{{ $supplier->message }}</p>
                                        </div>
                                    </div>
                                    <div class="profile-personal-info mt-4">
                                        <h4 class="text-primary mb-4">{{ __('captions.additional_information') }}</h4>
                                        <div class="row mb-2">
                                            <div class="col-3">
                                                <h5 class="f-w-500">{{ __('captions.inn') }} <span class="pull-right">:</span></h5>
                                            </div>
                                            <div class="col-9"><span>{{ $supplier->inn }}</span></div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-3">
                                                <h5 class="f-w-500">{{ __('captions.organization_type') }} <span class="pull-right">:</span></h5>
                                            </div>
                                            <div class="col-9"><span>{{ $supplier->type_of_organisation }}</span></div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-3">
                                                <h5 class="f-w-500">{{ __('captions.working_regions') }} <span class="pull-right">:</span></h5>
                                            </div>
                                            <div class="col-9"><span>{{ __('captions.no_regions_specified') }}</span></div>
                                        </div>
                                    </div>
                                </div>
                                @if($currentUser && $currentUser->id === $supplier->user_id)
                                    <div id="profile-settings" class="tab-pane fade">
                                        <div class="pt-3">
                                            <div class="settings-form">
                                                <h4 class="text-primary">{{ __('captions.profile_settings') }}</h4>
                                                <form action="" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <!-- Add form fields for updating supplier information -->
                                                    <button class="btn btn-primary" type="submit">{{ __('captions.save_changes') }}</button>
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
<style>
    .left-aligned-tabs {
        justify-content: flex-start !important;
    }
</style>