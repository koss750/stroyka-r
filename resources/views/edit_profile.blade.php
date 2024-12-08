@extends('layouts.alternative')

@section('canonical', '')

@section('additional_head')
<title>Мои данные</title>
<meta name="description" content="Мои данные на портале Стройка.com. Здесь вы можете просматривать и редактировать свои данные.">
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
                                <input type="text" disabled class="form-control" id="company_name" name="supplier[company_name]" value="{{ old('supplier.company_name', $user->supplier->company_name) }}">
                            </div>

                            <div class="mb-3">
                                <label for="inn" class="form-label">ИНН</label>
                                <input type="text" class="form-control" id="inn" name="supplier[inn]" value="{{ old('supplier.inn', $user->supplier->inn) }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label for="legal_address" class="form-label">Юридический адрес</label>
                                <input type="text" class="form-control" id="legal_address" name="supplier[legal_address]" value="{{ old('supplier.legal_address', $user->supplier->legal_address) }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label for="physical_address" class="form-label">Фактический адрес</label>
                                <input type="text" class="form-control" id="physical_address" name="supplier[physical_address]" value="{{ old('supplier.physical_address', $user->supplier->physical_address) }}" disabled>
                            </div>

                            <div class="mb-3">
                                <label for="yandex_maps_link" class="form-label">Ссылка на Яндекс.Карты</label>
                                <input type="text" class="form-control no-text-transform" id="yandex_maps_link" name="supplier[yandex_maps_link]" value="{{ old('supplier.yandex_maps_link', $user->supplier->yandex_maps_link) }}">
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Телефон 1</label>
                                <input type="text" class="form-control" id="phone" name="supplier[phone]" value="{{ old('supplier.phone', $user->supplier->phone) }}">
                            </div>

                            <div class="mb-3">
                                <label for="additional_phone" class="form-label">Телефон 2</label>
                                <input type="text" class="form-control" id="additional_phone" name="supplier[additional_phone]" value="{{ old('supplier.additional_phone', $user->supplier->additional_phone) }}">
                            </div>

                            <div class="mb-3">
                                <label for="type_of_organisation" class="form-label">Тип организации</label>
                                <select class="form-select" disabled id="type_of_organisation" name="supplier[type_of_organisation]">
                                    @foreach($organizationTypes as $value => $label)
                                        <option value="{{ $value }}" {{ old('supplier.type_of_organisation', $user->supplier->type_of_organisation) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Сообщение</label>
                                <textarea class="form-control no-text-transform" id="message" name="supplier[message]" rows="3">{{ old('supplier.message', $user->supplier->message) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="main_region" class="form-label">Основной регион</label>
                                <input type="text" class="form-control" id="main_region " name="supplier[main_region]" value="Москва" disabled>
                            </div>

                            <div class="mb-3">
                                <label for="region-input" class="form-label">Регионы оказания услуг по строительству:</label>
                                <div id="region-dropdown" class="region-dropdown" style="display: none;"></div>
                                <div id="selected-regions" class="mt-2"></div>
                                <input type="hidden" id="region-codes" name="supplier[regions]">
                                <input type="text" class="form-control" id="region-input" placeholder="Добавить регионы (введите название региона)">
                                <div id="region-error" class="text-danger mt-2" style="display: none;">
                                    Пожалуйста, выберите хотя бы один регион.
                                </div>
                            </div>
                        @else
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h4>Регистрация юридического лица</h4>
                                </div>
                                <div class="card-body">
                                    <p class="mb-3">
                                        Вы можете зарегистрироваться как юридическое лицо для предоставления услуг на платформе.
                                    </p>
                                    <a href="{{ url('/register-legal') }}" class="btn btn-primary">
                                        Зарегистрировать юридическое лицо
                                    </a>
                                </div>
                            </div>
                        @endif


                        <div class="mb-3">
                            <label for="password" class="form-label">Новый пароль (оставьте пустым, если не хотите менять)</label>
                            <input type="text" class="form-control" id="password" name="password" style="text-transform: none;">
                        </div>

                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional_scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const regionInput = document.getElementById('region-input');
    const regionDropdown = document.getElementById('region-dropdown');
    const regionCodesInput = document.getElementById('region-codes');
    const regionError = document.getElementById('region-error');
    const selectedRegionsContainer = document.getElementById('selected-regions');
    let regions = [];
    let selectedRegions = new Map();

    // Initialize selected regions from the supplier's existing regions
    @if($user->supplier && $user->supplier->regions)
        @foreach($user->supplier->regions as $region)
            selectedRegions.set('{{ $region->id }}', '{{ $region->name }}');
        @endforeach
    

    // Fetch regions from the server
    fetch('/api/regions')
        .then(response => response.json())
        .then(data => {
            regions = data;
            // Update display of initially selected regions
            updateSelectedRegionsDisplay();
            updateRegionCodes();
            // Don't show dropdown initially
            regionDropdown.style.display = 'none';
        })
        .catch(error => console.error('Error fetching regions:', error));

    // Show dropdown only when clicking the input
    regionInput.addEventListener('click', function() {
        renderDropdown(regions);
        regionDropdown.style.display = 'block';
    });

    regionInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const filteredRegions = regions.filter(region => 
            region.name.toLowerCase().includes(searchTerm)
        );

        renderDropdown(filteredRegions);
    });

    function renderDropdown(filteredRegions) {
        if (filteredRegions.length === 0) {
            regionDropdown.style.display = 'none';
            return;
        }

        regionDropdown.innerHTML = filteredRegions.map(region => {
            const isSelected = selectedRegions.has(region.id.toString());

            return `
                <div class="region-item">
                    <label>
                        <input type="checkbox" class="region-checkbox" 
                               data-id="${region.id}" 
                               data-name="${region.name}" 
                               ${isSelected ? 'checked' : ''}>
                        ${region.name}
                    </label>
                </div>
            `;
        }).join('');

        regionDropdown.style.display = 'block';

        document.querySelectorAll('.region-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const regionId = this.dataset.id;
                const regionName = this.dataset.name;
                if (this.checked) {
                    addRegion(regionId, regionName);
                } else {
                    removeRegion(regionId);
                }
                updateInputField();
            });
        });
    }

    function addRegion(id, name) {
        selectedRegions.set(id, name);
        updateRegionCodes();
        validateRegionSelection();
        updateSelectedRegionsDisplay();
        updateInputField();
    }

    function removeRegion(id) {
        selectedRegions.delete(id);
        updateRegionCodes();
        validateRegionSelection();
        updateSelectedRegionsDisplay();
        updateInputField();
        renderDropdown(regions);
    }

    function updateRegionCodes() {
        regionCodesInput.value = Array.from(selectedRegions.keys()).join(',');
    }

    function updateInputField() {
        // Clear the input field
        regionInput.value = '';
        // Remove any placeholder if regions are selected
        if (selectedRegions.size > 0) {
            regionInput.placeholder = 'Добавить регионы (введите название региона)';
        }
    }

    function validateRegionSelection() {
        if (selectedRegions.size === 0) {
            regionError.style.display = 'block';
        } else {
            regionError.style.display = 'none';
        }
    }

    function updateSelectedRegionsDisplay() {
        selectedRegionsContainer.innerHTML = Array.from(selectedRegions.entries()).map(([id, name]) => `
            <span class='selected-region'>
                ${name}
                <span class='remove-region' data-id='${id}'>×</span>
            </span>
        `).join("");

        // Add click handlers for remove buttons
        document.querySelectorAll('.remove-region').forEach(button => {
            button.addEventListener('click', function() {
                const regionId = this.dataset.id;
                removeRegion(regionId);
                // Update checkbox in dropdown if it's visible
                const checkbox = document.querySelector(`.region-checkbox[data-id="${regionId}"]`);
                if (checkbox) {
                    checkbox.checked = false;
                }
            });
        });
    }
    
    document.addEventListener('click', function(event) {
        if (!regionDropdown.contains(event.target) && event.target !== regionInput) {
            regionDropdown.style.display = 'none';
        }
    });
    
    // Add a focus event listener to clear the input when clicked
    regionInput.addEventListener('focus', function() {
        this.value = '';
    });

    // Add a blur event listener to handle when input loses focus
    regionInput.addEventListener('blur', function() {
        // Small delay to allow for checkbox clicks to register
        setTimeout(() => {
            if (!regionDropdown.contains(document.activeElement)) {
                updateInputField();
            }
        }, 200);
    });
    @endif
    // Get phone input elements
    const phoneInput = document.getElementById('phone');
    
    function formatPhoneNumber(input) {
        const placeholder = '+7 (___) ___-__-__';
        
        input.placeholder = placeholder;

        input.addEventListener('focus', function() {
            if (this.value === '') {
                this.value = '+7 (';
            }
        });

        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length > 0 && value[0] !== '7') {
                value = '7' + value;
            }
            
            let formattedValue = '';
            if (value.length > 0) {
                formattedValue = '+7 ';
                if (value.length > 1) {
                    formattedValue += '(' + value.substring(1, 4);
                }
                if (value.length > 4) {
                    formattedValue += ') ' + value.substring(4, 7);
                }
                if (value.length > 7) {
                    formattedValue += '-' + value.substring(7, 9);
                }
                if (value.length > 9) {
                    formattedValue += '-' + value.substring(9, 11);
                }
            }
            
            e.target.value = formattedValue;
        });

        input.addEventListener('blur', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length < 11) {
                e.target.value = '';
                e.target.placeholder = placeholder;
            }
        });
    }

    // Initialize phone formatting
    formatPhoneNumber(phoneInput);

    // If there's existing content in the phone field, format it
    if (phoneInput.value) {
        const event = new Event('input');
        phoneInput.dispatchEvent(event);
    }
});
</script>
@endsection

<style>
.region-dropdown {
    position: absolute;
    background-color: white;
    border: 1px solid #ced4da;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    width: 100%;
    margin-top: -25px;
}

.region-item {
    padding: 5px 10px;
    cursor: pointer;
}

.region-item:hover {
    background-color: #f8f9fa;
}

.selected-region {
    display: inline-block;
    background-color: #e9ecef;
    padding: 5px 10px;
    margin: 5px;
    border-radius: 5px;
}

.remove-region {
    margin-left: 5px;
    cursor: pointer;
    font-weight: bold;
    color: #666;
    padding: 0 3px;
}

.remove-region:hover {
    color: #dc3545;
}
</style>