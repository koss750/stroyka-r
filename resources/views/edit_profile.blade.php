@extends('layouts.alternative')

@section('content')
<div class="container-fluid">
    @include('partials.tab-navigator', ['items' => [
        ['url' => '/my-orders', 'label' => 'Заказы'],
        ['url' => '/suppliers', 'label' => 'Строители'],
        ['url' => '/messages', 'label' => 'Мои переписки'],
        ['url' => '/profile', 'label' => 'Мои данные'],
    ]])

    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Настройки профиля</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('user.update') }}" method="POST">
                        @csrf
                        @method('POST')

                        <div class="mb-3">
                            <label for="name" class="form-label">Имя</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control no-text-transform" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Телефон</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                        </div>

                        @if(!$user->supplier)
                            <div class="mb-3">
                                <label for="region_id" class="form-label">Основной регион</label>
                                <select class="form-select" id="region_id" name="region_id" required>
                                    @foreach($regions as $region)
                                        <option value="{{ $region->id }}" {{ old('region_id', $user->regions) == $region->id ? 'selected' : '' }}>
                                            {{ $region->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" id="email_notifications" type="checkbox" name="email_notifications" {{ $user->email_notifications ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_notifications">
                                    Получать уведомления по email
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" id="sms_notifications" type="checkbox" name="sms_notifications" {{ $user->sms_notifications ? 'checked' : '' }}>
                                <label class="form-check-label" for="sms_notifications">
                                    Получать SMS-уведомления
                                </label>
                            </div>
                        </div>

                        @if($user->supplier)
                            <h5 class="mt-4 mb-3">Информация о поставщике</h5>

                            <div class="mb-3">
                                <label for="company_name" class="form-label">Название компании</label>
                                <input type="text" class="form-control" id="company_name" name="supplier[company_name]" value="{{ old('supplier.company_name', $user->supplier->company_name) }}">
                            </div>

                            <div class="mb-3">
                                <label for="inn" class="form-label">ИНН</label>
                                <input type="text" class="form-control" id="inn" name="supplier[inn]" value="{{ old('supplier.inn', $user->supplier->inn) }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Адрес</label>
                                <input type="text" class="form-control" id="address" name="supplier[address]" value="{{ old('supplier.address', $user->supplier->address) }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label for="phone_1" class="form-label">Телефон 1</label>
                                <input type="text" class="form-control" id="phone_1" name="supplier[phone_1]" value="{{ old('supplier.phone_1', $user->supplier->phone_1) }}">
                            </div>

                            <div class="mb-3">
                                <label for="phone_2" class="form-label">Телефон 2</label>
                                <input type="text" class="form-control" id="phone_2" name="supplier[phone_2]" value="{{ old('supplier.phone_2', $user->supplier->phone_2) }}">
                            </div>

                            <div class="mb-3">
                                <label for="type" class="form-label">Тип поставщика</label>
                                <select class="form-select" id="type" name="supplier[type]">
                                    @foreach($supplierTypes as $value => $label)
                                        <option value="{{ $value }}" {{ old('supplier.type', $user->supplier->type) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="type_of_organisation" class="form-label">Тип организации</label>
                                <select class="form-select" id="type_of_organisation" name="supplier[type_of_organisation]">
                                    @foreach($organizationTypes as $value => $label)
                                        <option value="{{ $value }}" {{ old('supplier.type_of_organisation', $user->supplier->type_of_organisation) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Сообщение</label>
                                <textarea class="form-control" id="message" name="supplier[message]" rows="3">{{ old('supplier.message', $user->supplier->message) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="regions" class="form-label">Регионы</label>
                                <select class="form-select" id="regions" name="supplier[regions][]" multiple>
                                    @foreach($regions as $region)
                                        <option value="{{ $region->id }}" {{ in_array($region->id, old('supplier.regions', $user->supplier->regions->pluck('id')->toArray())) ? 'selected' : '' }}>
                                            {{ $region->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="password" class="form-label">Новый пароль (оставьте пустым, если не хотите менять)</label>
                            <input type="text" class="form-control" id="password" name="password">
                        </div>

                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection