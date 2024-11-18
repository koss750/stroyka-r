@extends('layouts.alternative')

@section('content')
<script src="https://pay.yandex.ru/sdk/v1/pay.js" onload="onYaPayLoad()" async> </script>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-5">
                <div class="card-body">
                    <h2 class="text-center mb-4">Подтверждение регистрации юридического лица</h2>
                    
                    <div class="alert alert-info" role="alert">
                        <h4 class="alert-heading">Важное уведомление:</h4>
                        <p>Вас приветствует уникальный информационный ресурс для коммуникации потребителей рынка малоэтажного деревянного домостроения и компаний подрядчиков!</p>
                        <p>В интересах обеспечения качества и безопасности производится регистрация и проверка юридических лиц.</p>
                        <p>Информационная платформа является платной. После регистрации в течении первых 30 календарных дней, действуют особые правила подписки. Стоимость услуги составляет всего 500 рублей.</p>
                        <p>Подписка дает право размещения в реестре исполнителей, список исполнителей выдается клиентам вместе со сметой, приобретаемой ими на нашем ресурсе.</p>
                        <hr>
                        <p class="mb-0">Оплата находится в конце страницы. По любым вопросам вы можете связаться с нашей службой поддержки по адресу <a href="mailto:info@стройка.com">info@стройка.com</a>.</p>
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
                                    <input type="text" id="legal_address" name="legal_address" value="{{ $legal_address }}" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="physical_address">Фактический адрес:</label>
                                    <input type="text" id="physical_address" name="physical_address" value="{{ $physical_address }}" class="form-control no-text-transform" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" id="email" name="email" class="form-control no-text-transform" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Телефон:</label>
                                    <input type="tel" id="phone" name="phone" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="additional_phone">Дополнительный телефон:</label>
                                    <input type="tel" id="additional_phone" name="additional_phone" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="contact_name">Контактное лицо:</label>
                                    <input type="text" id="contact_name" name="contact_name" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Создайте пароль:</label>
                                    <div class="input-group">
                                        <input type="password" id="password" name="password" class="form-control" required>
                                        <span class="input-group-text toggle-password" data-target="#password">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Повторите пароль:</label>
                                    <div class="input-group">
                                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                                        <span class="input-group-text toggle-password" data-target="#confirm_password">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div id="address-suggestions" class="suggestions-container-static"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="main_region">Основной регион:</label>
                            <select id="main_region" name="main_region" class="form-control" required>
                                <option value="">Выберите основной регион</option>
                            </select>
                        </div>

                        <div class="form-group mt-4">
                            <label for="region-input">Поиск и выбор дополнительныхрегионов:</label>
                            <input type="text" class="form-control" id="region-input" placeholder="Начните вводить название региона">
                            <div id="region-dropdown" class="region-dropdown" style="display: none;"></div>
                            <div id="selected-regions" class="mt-2"></div>
                            <input type="hidden" id="region-codes" name="region_codes">
                            <div id="region-error" class="text-danger mt-2" style="display: none;">
                                Пожалуйста, выберите хотя бы один регион.
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <p>Для завершения регистрации и передачи данных на проверку, пожалуйста, нажмите кнопку ниже:</p>
                        </div>
                        <div class="row justify-content-center">
                            <div id="error_container" class="col-md-12 text-center text-danger" style="display: none;">
                                <p id="error_message">Ошибка при вводе данных</p>
                            </div>    
                            <div class="col-md-12 text-center">
                                <p>1 рубль</p>
                            </div>    
                            <div class="col-md-12 text-center">
                                <div id="button_container"></div>
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
    // Function to find the closest matching region
    function findClosestRegion(legalAddress, allRegions) {
        // Normalize the address region
        let normalizedAddressRegion = normalizeString(legalAddress.split(',')[0].trim());
        let closestRegion = null;
        let highestSimilarity = 0;

        
        allRegions.forEach(region => {
            ////console.log("checking", normalizedAddressRegion, "against", region.name);
            const normalizedRegionName = normalizeString(region.name);
            
            // Count matching characters
            const charMatches = new Set([...normalizedAddressRegion].filter(char => normalizedRegionName.includes(char))).size;

            // Count matching words
            const addressWords = normalizedAddressRegion.split(/\s+/);
            const regionWords = normalizedRegionName.split(/\s+/);
            const wordMatches = addressWords.filter(word => regionWords.some(w2 => w2.includes(word) || word.includes(w2))).length;

            // Calculate similarity scores
            const charSimilarity = charMatches / Math.max(normalizedAddressRegion.length, normalizedRegionName.length);
            const wordSimilarity = wordMatches / Math.max(addressWords.length, regionWords.length);

            // Combine scores (you can adjust weights if needed)
            const similarity = (charSimilarity + wordSimilarity) / 2;
            if (similarity > highestSimilarity) {
                highestSimilarity = similarity;
                closestRegion = region.code;
            }
        });

        ////console.log("Similarity of :", highestSimilarity);
        return closestRegion;
    }

    // Fetch regions from the server
    fetch('/api/regions')
        .then(response => response.json())
        .then(data => {
            regions = data;
            const closestRegionCode = findClosestRegion("{{ $legal_address }}", data);
            //console.log("Closest region code:", closestRegionCode); // Use //console.log for debugging

            // Populate main region dropdown
            regions.forEach(region => {
                const option = document.createElement('option');
                option.value = region.code;
                option.textContent = region.name;
                
                // Check if this region is the closest match
                if (region.code === closestRegionCode) {
                    option.selected = true;
                }
                
                mainRegionSelect.appendChild(option);
            });

            // Ensure the closest region is selected (as a fallback)
            if (closestRegionCode) {
                mainRegionSelect.value = closestRegionCode;
            }

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
});
</script>
<script>
    
function onYaPayLoad() {
    const YaPay = window.YaPay;

    // Payment data
    const paymentData = {
        env: YaPay.PaymentEnv.Sandbox,
        version: 4,
        currencyCode: YaPay.CurrencyCode.Rub,
        merchantId: 'cc08ce31-8375-439d-86f1-1201533f53e7',
        totalAmount: 1,
        availablePaymentMethods: ['CARD'],
    };

    function refuseSubmit($error_message, $error_element = []) {
        document.getElementById('error_container').style.display = 'block';
        document.getElementById('error_message').textContent = $error_message;
        $error_element.forEach(element => {
            let el = document.getElementById(element);
            el.classList.add('is-invalid');
        });
        throw new Error($error_message);
    }

    async function onPayButtonClick() {
        try {
            const offer_id = 1; //offer ID 1 refers to initial offer of 1 ruble per month during startup period
            const url = '{{ route('process-membership-order') }}';
            //check if any "required" fields are empty
            const requiredFields = ['password', 'confirm_password', 'inn', 'company_name', 'contact_name', 'email', 'phone'];
            requiredFields.forEach(field => {
                if (document.getElementById(field).value === '') {
                    refuseSubmit(`Поле ${field} не может быть пустым`);
                }
            });
            if (document.getElementById('password').value !== document.getElementById('confirm_password').value) {
                refuseSubmit('Пароли не совпадают', ['password', 'confirm_password']);
            }
            // Send request to your server to handle the Yandex Pay order creation
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    payment_provider: 'yandex',
                    payment_amount: 1,
                    order_type: 'membership',
                    inn: document.getElementById('inn').value,
                    company_name: document.getElementById('company_name').value,
                    name: document.getElementById('contact_name').value,
                    email: document.getElementById('email').value,
                    phone: document.getElementById('phone').value,
                    additional_phone: document.getElementById('additional_phone').value,
                    password: document.getElementById('password').value,
                    legal_address: document.getElementById('legal_address').value,
                    physical_address: document.getElementById('physical_address').value,
                    main_region: document.getElementById('main_region').value,
                    region_codes: document.getElementById('region-codes').value,
                    offer_id: offer_id,
                })
            });

            const data = await response.json();

            if (data.success) {
                // Return the payment URL from your server response
                return data.paymentUrl;
            } else {
                throw new Error('Failed to start membership');
            }
        } catch (error) {
            console.error('Error starting membership:', error);
            throw error;
        }
    }

    // Error handler for payment form opening
    function onFormOpenError(reason) {
        console.error(`Payment error — ${reason}`);
    }

    // Create Yandex Pay session
    YaPay.createSession(paymentData, {
        onPayButtonClick: onPayButtonClick,
        onFormOpenError: onFormOpenError,
    })
        .then(function (paymentSession) {
            // Mount the Yandex Pay button
            paymentSession.mountButton(document.querySelector('#button_container'), {
                type: YaPay.ButtonType.Pay,
                theme: YaPay.ButtonTheme.Black,
                width: YaPay.ButtonWidth.Max,
            });
            console.log('Payment session created successfully');
        })
        .catch(function (err) {
            console.error('Failed to create payment session:', err);
        });
}
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




