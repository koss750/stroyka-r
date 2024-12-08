@extends('layouts.alternative')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-5">
                <div class="card-body">
                    <h2 class="text-center mb-4">Подтверждение регистрации юридического лица</h2>
                    
                    <div class="alert alert-info" role="alert">
                        <h4 class="alert-heading">Важное уведомление!</h4>
                        <p>Вас приветствует уникальный информационный ресурс для коммуникации потребителей рынка малоэтажного деревянного домостроения и компаний подрядчиков!</p>
                        <p>В интересах обеспечения качества и безопасности производится регистрация и проверка юридических лиц.</p>
                        <p>Мы предлагаем специальную промо-цену на подписку - всего 1 рубль. Обращаем ваше внимание! После достижения определенного количества обращений к компаниям от наших пользователей, стоимость подписки будет пересмотрена.</p>
                        <p>Подписка дает право размещения в реестре исполнителей, список исполнителей выдается клиентам вместе со сметой, приобретаемой ими на нашем ресурсе.</p>
                        <p>Все оформленные подписки будут действовать до окончания оплаченного периода. О любых изменениях условий мы заблаговременно уведомим всех пользователей, за 7 календарных дней.</p>
                        <hr>
                        <p class="mb-0">По любым вопросам вы можете связаться с нашей службой поддержки по адресу <a href="mailto:info@стройка.com">info@стройка.com</a>.</p>
                    </div>

                    <form id="legal-entity-form">
                        <h4 class="mt-4 mb-3">Данные вашей регистрации:</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_name">Название компании:</label>
                                    <input type="text" id="company_name" name="company_name" value="{{ $company_name }}" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="inn">ИНН:</label>
                                    <input type="text" id="inn" name="inn" value="{{ $inn }}" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="kpp">КПП:</label>
                                    <input type="text" id="kpp" name="kpp" value="{{ $kpp }}" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="ogrn">ОГРН:</label>
                                    <input type="text" id="ogrn" name="ogrn" value="{{ $ogrn }}" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="legal_address">Юридический адрес:</label>
                                    <input type="text" id="legal_address" name="legal_address" value="{{ $legal_address }}" class="form-control no-text-transform" readonly>
                                </div>
                                <input type="hidden" id="main_region_id" name="main_region_id" value="{{ $main_region_id }}">
                                <div class="form-group">
                                    <label for="main_region">Регион юридического адреса: <span class="text-danger">*</span></label>
                                    <select id="main_region" name="main_region" class="form-control" disabled>
                                        <option value="">Выберите основной регион</option>
                                    </select>
                                </div>
                                
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email: <span class="text-danger">*</span></label>
                                    <input type="email" id="email" name="email" 
                                           class="form-control no-text-transform" 
                                           value="{{ auth()->check() ? auth()->user()->email : '' }}"
                                           {{ auth()->check() ? 'disabled' : 'required' }}>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Телефон: <span class="text-danger">*</span></label>
                                    <input type="tel" id="phone" name="phone" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="additional_phone">Дополнительный телефон:</label>
                                    <input type="tel" id="additional_phone" name="additional_phone" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="contact_name">Контактное лицо: <span class="text-danger">*</span></label>
                                    <input type="text" id="contact_name" name="contact_name" class="form-control" required>
                                </div>
                                @if(!auth()->check())
                                    <div class="form-group">
                                        <label for="password">Создайте пароль: <span class="text-danger">*</span></label>
                                        <div class="input-group" style="margin-bottom: -4px;">
                                            <input type="password" id="password" name="password" class="form-control no-text-transform" required>
                                            <span class="input-group-text toggle-password" data-target="#password">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                        </div>
                                        <div id="password-error-message" class="text-danger mt-1"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm_password">Повторите пароль: <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" id="confirm_password" name="confirm_password" class="form-control no-text-transform" required>
                                            <span class="input-group-text toggle-password" data-target="#confirm_password">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" id="password" name="password" value="existing_password">
                                    <input type="hidden" id="confirm_password" name="confirm_password" value="existing_password">
                                @endif
                            </div>
                            <div class="col-md-12">
                                <div id="address-suggestions" class="suggestions-container-static"></div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-4">
                                    <label for="physical_address">Фактический адрес: <span class="text-danger">*</span></label>
                                    <input type="text" id="physical_address" name="physical_address" value="{{ $physical_address }}" class="form-control no-text-transform" required>
                                </div>
                        <div class="form-group mt-4">
                            <label for="region-input">Поиск и выбор дополнительных регионов оказания услуг по строительству:</label>
                            <input type="text" class="form-control" id="region-input" placeholder="Начните вводить название региона">
                            <div id="region-dropdown" class="region-dropdown" style="display: none;"></div>
                            <div id="selected-regions" class="mt-2"></div>
                            <input type="hidden" id="region-codes" name="region_codes">
                            <div id="region-error" class="text-danger mt-2" style="display: none;">
                                Пожалуйста, выберите хотя бы один регион.
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="yandex_maps_link">Ссылка на Яндекс Карты (используется для оценки рейтинга и ссылки на отзывы):</label>
                            <input type="text" id="yandex_maps_link" name="yandex_maps_link" placeholder="Вставьте ссылку на Яндекс Карты, например: https://yandex.ru/maps/..." class="form-control">
                        </div>

                        <div class="mt-4 text-center">
                            <p>Для завершения регистрации и передачи данных на проверку, пожалуйста, нажмите кнопку ниже:</p>
                        </div>
                        <div class="row justify-content-center">
                            <div id="error_container" class="col-md-12 text-center text-danger" style="display: none;">
                                <p id="error_message"></p>
                            </div>    
                            <div class="col-md-12 text-center">
                                <div id="error_message" class="alert alert-danger mt-3" style="display: none;"></div>
                                <button type="button" class="btn btn-primary" id="confirmRegistration" disabled>
                                    Оплатить 1 рубль
                                </button>
                            </div>    
                        </div>
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
    const mainRegionSelect = document.getElementById('main_region');
    const legalAddress = "{{ $legal_address }}";
    const physicalAddressInput = document.getElementById('physical_address');
    const suggestionsContainer = document.getElementById('address-suggestions');
    const phoneInput = document.getElementById('phone');
    const additionalPhoneInput = document.getElementById('additional_phone');
    const token = "{{ env('DADATA_API') }}"; // Using the token from .env file
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const confirmButton = document.getElementById('confirmRegistration');
    const errorMessageDiv = document.getElementById('error_message');
    const errorContainer = document.getElementById('error_container');
    const confirmPasswordField = document.getElementById('confirm_password');

    //same for phone and additional phone
    formatPhoneNumber(phoneInput);
    formatPhoneNumber(additionalPhoneInput);

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

    function normalizeString(str) {
        return str.toLowerCase().replace(/[.,\/#!$%\^&\*;:{}=\-_`~()]/g, "").trim();
    }

    let regions = [];
    let selectedRegions = new Map(); // Changed to Map to store both code and name
    // Define city to region mapping
    const cityToRegion = {
        'екатеринбург': 'свердловская область',
        'выборг': 'ленинградская область',
        'нижний новгород': 'нижегородская область',
        'казань': 'республика татарстан',
        'уфа': 'республика башкортостан',
        'челябинск': 'челябинская область',
        'пермь': 'пермский край',
        'тюмень': 'тюменская область',
        'ижевск': 'удмуртская республика',
        'москва': 'московская область',
        'новосибирск': 'новосибирская область',
        'омск': 'омская область',
        'самара': 'самарская область',
        'краснодар': 'краснодарский край',
        'г курск': 'курская'
    };

   

    // Fetch regions from the server
    fetch('/api/regions')
        .then(response => response.json())
        .then(data => {
            regions = data;
            //const closestRegionCode = findClosestRegion("{{ $legal_address }}", data);
            //console.log("Closest region code:", closestRegionCode); // Use //console.log for debugging

            // Populate main region dropdown
            regions.forEach(region => {
                const option = document.createElement('option');
                option.value = region.code;
                option.textContent = region.name;
                
                // get from post values
                if (region.code == document.getElementById('main_region_id').value) {
                    option.selected = true;
                }
                
                mainRegionSelect.appendChild(option);
            });

            // Show all regions when the input is clicked
            regionInput.addEventListener('click', function() {
                renderDropdown(regions);
                this.select(); // Select all text in the input
            });
        })
        .catch(error => console.error('Error fetching regions:', error));

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

        const mainRegionCode = mainRegionSelect.value;

        regionDropdown.innerHTML = filteredRegions.map(region => {
            const isMainRegion = region.code === mainRegionCode;
            const isSelected = selectedRegions.has(region.code) || isMainRegion;

            return `
                <div class="region-item">
                    <label>
                        <input type="checkbox" class="region-checkbox" 
                               data-id="${region.code}" 
                               data-name="${region.name}" 
                               ${isSelected ? 'checked' : ''}
                               ${isMainRegion ? 'disabled' : ''}>
                        ${region.name} ${isMainRegion ? '(Основной регион)' : ''}
                    </label>
                </div>
            `;
        }).join('');

        regionDropdown.style.display = 'block';

        // Add change event listeners to checkboxes
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

        // Automatically add main region to selected regions
        if (mainRegionCode && !selectedRegions.has(mainRegionCode)) {
            const mainRegionName = mainRegionSelect.options[mainRegionSelect.selectedIndex].text;
            addRegion(mainRegionCode, mainRegionName);
            updateInputField();
        }
    }

    function addRegion(id, name) {
        //console.log("adding", id, name);
        updateSelectedRegionsDisplay();
        selectedRegions.set(id, name);
        updateRegionCodes();
        validateRegionSelection();
    }

    function removeRegion(id) {
        //console.log("removing", id);
        selectedRegions.delete(id);
        updateRegionCodes();
        validateRegionSelection();
        updateSelectedRegionsDisplay();
    }

    function updateRegionCodes() {
        
        regionCodesInput.value = Array.from(selectedRegions.keys()).join(',');
    }

    function updateInputField() {
        regionInput.value = Array.from(selectedRegions.values()).join(', ');
    }

    function validateRegionSelection() {
        if (selectedRegions.size === 0) {
            regionError.style.display = 'block';
        } else {
            regionError.style.display = 'none';
        }
    }

    function updateSelectedRegionsDisplay() {
        //console.log("updating array", selectedRegions);
        //selectedRegionsContainer.innerHTML = Array.from(selectedRegions).map(([id, name]) => "<span class='selected-region'>" + name + " (" + id + ")</span>").join(", ");

        // Add event listeners to remove buttons
        document.querySelectorAll('.remove-region').forEach(button => {
            button.addEventListener('click', function() {
                const regionId = this.dataset.id;
                removeRegion(regionId);
                updateInputField();
                renderDropdown(regions); // Re-render dropdown to update checkboxes
            });
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!regionDropdown.contains(event.target) && event.target !== regionInput) {
            regionDropdown.style.display = 'none';
        }
    });

    // Add form submission validation
    document.getElementById('legal-entity-form').addEventListener('submit', function(e) {
        if (selectedRegions.size === 0) {
            e.preventDefault();
            regionError.style.display = 'block';
            regionError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    physicalAddressInput.addEventListener('input', function() {
        if (this.value.length < 3) {
            suggestionsContainer.innerHTML = '';
            return;
        }

        fetch('https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address', {
            method: 'POST',
            mode: 'cors',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': 'Token ' + token
            },
            body: JSON.stringify({query: this.value})
        })
        .then(response => response.json())
        .then(result => {
            suggestionsContainer.innerHTML = '';
            const suggestions = result.suggestions.slice(0, 10); // Limit to 10 suggestions
            suggestions.forEach(suggestion => {
                const div = document.createElement('div');
                div.textContent = suggestion.value;
                div.classList.add('suggestion-item');
                div.addEventListener('click', function() {
                    physicalAddressInput.value = suggestion.value;
                    suggestionsContainer.innerHTML = '';
                });
                suggestionsContainer.appendChild(div);
            });
            suggestionsContainer.style.display = suggestions.length > 0 ? 'block' : 'none';
        })
        .catch(error => console.error('Error:', error));
    });

    // Close suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target !== physicalAddressInput && e.target !== suggestionsContainer) {
            suggestionsContainer.style.display = 'none';
        }
    });
    
    confirmButton.addEventListener('click', function() {
        const registrationForm = document.getElementById('legal-entity-form');
        const formData = new FormData(registrationForm);
        const endpoint = '/api/tinkoff/init';

        console.log('Submitting order to:', endpoint, formData);
        formData.append('order_type', 'registration');

        fetch(endpoint, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success === true) {
                window.location.href = data.paymentUrl;
            } else {
                errorMessageDiv.textContent = data.error_message || 'Произошла ошибка при обработке запроса';
                console.log(data.error_content);
                errorContainer.style.display = 'block';
                confirmButton.disabled = false; // Re-enable the button to allow retry
            }
        })
        .catch(error => {
            errorMessageDiv.textContent = 'Произошла ошибка при обработке запроса';
            console.log(data.error_content);
            errorContainer.style.display = 'block';
            confirmButton.disabled = false;
        });
    });
    // Add this function to check passwords match
    function validatePasswords() {
        let isValid = true;
        console.log("validating passwords", passwordInput.value);
        if (passwordInput.value === "existing_password") {
            console.log("password is existing, skipping validation");
            return true;
        }
        console.log("password is not existing, validating");
        let errorMessages = [];

        // Check for Latin letters only
        const latinLettersRegex = /^[A-Za-z0-9!@#$%^&*()_+\-=\[\]{};:'",.<>/?\\|`~]*$/;
        if (!latinLettersRegex.test(passwordInput.value)) {
            errorMessages.push('Пароль должен содержать только латинские буквы и спецсимволы');
            isValid = false;
        }

        // Check password length
        if (passwordInput.value.length < 8) {
            errorMessages.push('Пароль должен содержать минимум 8 символов');
            isValid = false;
        }

        // Check if passwords match
        if (confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
            errorMessages.push('Пароли не совпадают');
            confirmPasswordInput.classList.add('red-border');
            isValid = false;
        } else {
            confirmPasswordInput.classList.remove('red-border');
        }

        // Display or hide error messages
        if (!isValid) {
            errorMessageDiv.innerHTML = errorMessages.map(msg => `<div class="text-danger">${msg}</div>`).join('');
            errorContainer.style.display = 'block';
            document.getElementById('password-error-message').innerHTML = errorMessages.join('<br>');
        } else {
            errorMessageDiv.innerHTML = '';
            errorContainer.style.display = 'none';
            document.getElementById('password-error-message').innerHTML = '';
        }

        return isValid;
    }

    // Add these event listeners
    if (passwordInput.value !== "existing_password") {
        confirmPasswordInput.addEventListener('blur', validatePasswords);
        passwordInput.addEventListener('input', validatePasswords);
        confirmPasswordInput.addEventListener('input', validatePasswords);
    } else {
        passwordInput.value = "existing_password";
        confirmPasswordInput.value = "existing_password";
    }
    physicalAddressInput.addEventListener('input', validatePasswords);

    // Update the existing checkFormValidity function
    function checkFormValidity() {
        const isValid = form.checkValidity() && validatePasswords();
        confirmButton.disabled = !isValid;
    }

    // Add event listeners to all form inputs
    const form = document.getElementById('legal-entity-form');
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', checkFormValidity);
    });

    // Initial check
    checkFormValidity();
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
    }
    .modal-content-payment {
        margin: 5% auto;
        width: 90%;
        max-width: 100%;
    }
    #paymentModal .modal-content {
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    #paymentModal .modal-header {
        border-bottom: none;
        padding: 20px 30px;
    }
    #paymentModal .modal-body {
        padding: 20px 30px;
    }
    #paymentModal .form-control {
        border-radius: 5px;
        border: 1px solid #ced4da;
        padding: 10px 15px;
        line-height: normal;
    }
    #paymentModal .btn-primary {
        background-color: #007bff;
        border: none;
        border-radius: 5px;
        padding: 12px;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }
    #paymentModal .btn-primary:hover {
        background-color: #0056b3;
    }
    .card-type-icon {
        width: 40px;
        height: 25px;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
    }
    .card-type-visa {
        background-image: url('https://www.visa.com.ua/dam/VCOM/regional/ve/romania/blogs/hero-image/visa-logo-800x450.jpg');
    }
    .card-type-mastercard {
        background-image: url('https://platform.vox.com/wp-content/uploads/sites/2/chorus/uploads/chorus_asset/file/13674554/Mastercard_logo.jpg?quality=90&strip=all&crop=0,16.666666666667,100,66.666666666667');
    }
    .card-type-mir {
        background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTtw96jpVRrOTWcYjCpZLNpyV5ESnloAvRU5A&s');
    }

    .input-group-append {
        position: absolute;
        right: 10px;
        top: 19%;
        z-index: 10;
    }

    /* Add Yandex Pay button styles */
    .ya-pay-button {
        height: 40px !important;
        border-radius: 30px !important;
    }

    #loadingIndicator {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .suggestion-item {
        padding: 10px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
    }
    .suggestion-item:last-child {
        border-bottom: none;
    }
    .suggestion-item:hover {
        background-color: #f0f0f0;
    }
   
</style>




