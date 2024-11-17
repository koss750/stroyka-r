@extends('layouts.alternative')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card mt-5">
                <div class="card-body">
                    <h2 class="text-center mb-4">{{ __('Регистрация') }}</h2>
                    <ul class="nav nav-tabs mb-3" id="registerTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="individual-tab" data-bs-toggle="tab" data-bs-target="#individual" type="button" role="tab" aria-controls="individual" aria-selected="true">Физическое лицо</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="legal-tab" data-bs-toggle="tab" data-bs-target="#legal" type="button" role="tab" aria-controls="legal" aria-selected="false">Юридическое лицо</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="registerTabsContent">
                        <div class="tab-pane fade show active" id="individual" role="tabpanel" aria-labelledby="individual-tab">
                            <div class="alert alert-info mb-4" role="alert">
                                <h4 class="alert-heading">Важное уведомление:</h4>
                                <p>Вас приветствует уникальный строительный информационный ресурс Стройка.com</p>
                                <p>У нас вы можете в режиме онлайн получить смету любого понравившегося вам дома или бани с ресурса и одним кликом разослать запрос на строительство в десятки строительных компаний – партнеров ресурса.</p>
                                <p>Та компания что предложит вам оптимальные условия и станет воплощать вашу мечту в жизнь!</p>
                            </div>

                            <form id="signup-form-individual">
                                @csrf
                                <div class="mb-3">
                                    <label for="individual-name" class="form-label">Имя</label>
                                    <input type="text" class="form-control" id="individual-name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="individual-email" class="form-label">E-mail</label>
                                    <input type="email" class="form-control no-text-transform" id="individual-email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="individual-password" class="form-label">Пароль</label>
                                    <input type="password" class="form-control" id="individual-password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="individual-password-confirmation" class="form-label">Подтвердите пароль</label>
                                    <input type="password" class="form-control" id="individual-password-confirmation" name="password_confirmation" required>
                                </div>
                                <div class="mb-3">
                                    <label for="individual-region-select" class="form-label">Регион</label>
                                    <select class="form-control" id="individual-region-select" name="region_code" required>
                                        <option value="">Выберите Ваш Регион</option>
                                    </select>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="legal" role="tabpanel" aria-labelledby="legal-tab">
                            <form id="signup-form-legal">
                                @csrf
                                <div class="mb-3">
                                    <label for="inn" class="form-label">ИНН</label>
                                    <input type="text" class="form-control" id="inn" name="inn" required>
                                    <button type="button" id="check-inn" class="btn btn-secondary mt-2">Проверить</button>
                                </div>
                                <div id="company-confirmation" style="display: none;">
                                    <p>Найдена компания: <span id="found-company-name"></span></p>
                                    <p>Это ваша компания?</p>
                                    <button type="button" id="confirm-company" class="btn btn-primary">Да</button>
                                    <button type="button" id="reject-company" class="btn btn-secondary">Нет</button>
                                </div>
                                <div id="additional-fields" style="display: none;">
                                    <!-- Additional fields will be shown here after company confirmation -->
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary" style="display: none;">Зарегистрироваться</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Individual registration form handling
    const individualForm = document.getElementById('signup-form-individual');
    individualForm.addEventListener('submit', handleIndividualSubmit);

    // Legal entity registration form handling
    const legalForm = document.getElementById('signup-form-legal');
    legalForm.addEventListener('submit', handleLegalSubmit);

    // INN check functionality
    const innInput = document.getElementById('inn');
    const checkInnButton = document.getElementById('check-inn');
    const companyConfirmation = document.getElementById('company-confirmation');
    const foundCompanyName = document.getElementById('found-company-name');
    const confirmCompanyButton = document.getElementById('confirm-company');
    const rejectCompanyButton = document.getElementById('reject-company');
    const additionalFields = document.getElementById('additional-fields');
    const submitButton = legalForm.querySelector('button[type="submit"]');

    let lastCheckedInn = '';
    let isCompanyActive = false;

    checkInnButton.addEventListener('click', function() {
        const inn = innInput.value.trim();
        if (inn) {
            checkCompany(inn);
        } else {
            alert('Пожалуйста, введите ИНН');
        }
    });

    confirmCompanyButton.addEventListener('click', function() {
        if (isCompanyActive) {
            companyConfirmation.style.display = 'none';
            additionalFields.style.display = 'block';
            submitButton.style.display = 'block';
            checkInnButton.style.display = 'none';
            lastCheckedInn = innInput.value.trim();
        } else {
            alert('Извините, регистрация доступна только для активных компаний.');
            resetForm();
        }
    });

    rejectCompanyButton.addEventListener('click', function() {
        resetForm();
    });

    innInput.addEventListener('input', function() {
        if (innInput.value.trim() !== lastCheckedInn) {
            resetForm();
        }
    });

    // Load regions for individual registration
    loadRegions();

    function handleIndividualSubmit(e) {
        e.preventDefault();
        const formData = new FormData(individualForm);
        
        fetch('/api/register-individual', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                individualForm.reset();
            } else {
                alert('Ошибка при регистрации: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Произошла ошибка при отправке формы');
        });
    }

    function handleLegalSubmit(e) {
        e.preventDefault();
        const formData = new FormData(legalForm);
        
        fetch('/api/register-legal-entity', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                resetForm();
            } else {
                alert('Ошибка при регистрации: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Произошла ошибка при отправке формы');
        });
    }

    function resetForm() {
        companyConfirmation.style.display = 'none';
        additionalFields.style.display = 'none';
        submitButton.style.display = 'none';
        checkInnButton.style.display = 'block';
        lastCheckedInn = '';
        isCompanyActive = false;
        
        const inputs = legalForm.querySelectorAll('input:not(#inn)');
        inputs.forEach(input => input.value = '');
    }

    function checkCompany(inn) {
        fetch('/api/check-company', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ inn: inn })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                foundCompanyName.textContent = data.company_name;
                companyConfirmation.style.display = 'block';
                isCompanyActive = data.state_status === "ACTIVE";
                if (!isCompanyActive) {
                    alert('Внимание: эта компания не активна. Регистрация невозможна.');
                }
                populateFields(data);
            } else {
                alert('Компания не найдена. Пожалуйста, проверьте ИНН и попробуйте снова.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Произошла ошибка при проверке ИНН');
        });
    }

    function populateFields(data) {
        additionalFields.innerHTML = `
            <div class="mb-3">
                <label for="company_name" class="form-label">Название фирмы</label>
                <input type="text" class="form-control" id="company_name" name="company_name" value="${data.company_name}" readonly>
            </div>
            <div class="mb-3">
                <label for="kpp" class="form-label">КПП</label>
                <input type="text" class="form-control" id="kpp" name="kpp" value="${data.kpp}" readonly>
            </div>
            <div class="mb-3">
                <label for="ogrn" class="form-label">ОГРН</label>
                <input type="text" class="form-control" id="ogrn" name="ogrn" value="${data.ogrn}" readonly>
            </div>
            <div class="mb-3">
                <label for="legal_address" class="form-label">ЮР. адрес фирмы</label>
                <input type="text" class="form-control" id="legal_address" name="legal_address" value="${data.address}" readonly>
            </div>
            <div class="mb-3">
                <label for="physical_address" class="form-label">ФИЗ. Адрес</label>
                <input type="text" class="form-control no-text-transform" id="physical_address" name="physical_address" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Адрес эл. Почты</label>
                <input type="email" class="form-control no-text-transform" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Номер телефона организации</label>
                <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="mb-3">
                <label for="additional_phone" class="form-label">Второй контактный номер тел.</label>
                <input type="text" class="form-control" id="additional_phone" name="additional_phone" required>
            </div>
            <div class="mb-3">
                <label for="contact_name" class="form-label">Имя контактного лица</label>
                <input type="text" class="form-control" id="contact_name" name="contact_name" required>
            </div>
            <div class="mb-3">
                <label for="legal-password" class="form-label">Пароль</label>
                <input type="password" class="form-control" id="legal-password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="legal-password-confirmation" class="form-label">Подтвердите пароль</label>
                <input type="password" class="form-control" id="legal-password-confirmation" name="password_confirmation" required>
            </div>
            <div class="mb-3">
                <label for="region-input" class="form-label">Поиск регионов</label>
                <input type="text" class="form-control" id="region-input" placeholder="Введите регионы предоставления ваших услуг">
                <div id="region-suggestions" class="region-suggestions"></div>
                <div id="selected-regions" class="mt-2"></div>
                <input type="hidden" id="region-codes" name="region_codes">
            </div>
        `;

        // Initialize region selection functionality
        initializeRegionSelection();

        // Format phone number inputs
        formatPhoneNumber(document.getElementById('phone'));
        formatPhoneNumber(document.getElementById('additional_phone'));
    }

    function loadRegions() {
        fetch('/api/regions')
            .then(response => response.json())
            .then(regions => {
                const select = document.getElementById('individual-region-select');
                regions.forEach(region => {
                    const option = new Option(`${region.name} (${region.code})`, region.code);
                    select.add(option);
                });
            });
    }

    function initializeRegionSelection() {
        const regionInput = document.getElementById('region-input');
        const regionSuggestions = document.getElementById('region-suggestions');
        const selectedRegionsContainer = document.getElementById('selected-regions');
        const regionCodesInput = document.getElementById('region-codes');
        let regions = [];
        let selectedRegions = new Set();

        fetch('/api/regions')
            .then(response => response.json())
            .then(data => {
                regions = data;
                displayAllRegions();
            })
            .catch(error => console.error('Error fetching regions:', error));

        regionInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const filteredRegions = regions.filter(region => 
                region.name.toLowerCase().includes(query) ||
                region.code.toLowerCase().includes(query)
            );
            displayAllRegions(filteredRegions);
        });

        function displayAllRegions(regionsToDisplay = regions) {
            regionSuggestions.innerHTML = '';
            regionsToDisplay.forEach(region => {
                const div = document.createElement('div');
                div.className = 'region-option';
                
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.id = `region-${region.code}`;
                checkbox.checked = selectedRegions.has(region.code);
                checkbox.addEventListener('change', () => toggleRegion(region));

                const label = document.createElement('label');
                label.htmlFor = `region-${region.code}`;
                label.textContent = `${region.name} (${region.code})`;

                div.appendChild(checkbox);
                div.appendChild(label);
                regionSuggestions.appendChild(div);
            });
        }

        function toggleRegion(region) {
            if (selectedRegions.has(region.code)) {
                selectedRegions.delete(region.code);
            } else {
                selectedRegions.add(region.code);
            }
            updateSelectedRegionsDisplay();
            updateRegionCodes();
        }

        function updateSelectedRegionsDisplay() {
            selectedRegionsContainer.innerHTML = '';
            selectedRegions.forEach(code => {
                const region = regions.find(r => r.code === code);
                if (region) {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-primary me-2 mb-2';
                    badge.textContent = `${region.name} (${region.code})`;
                    
                    const removeBtn = document.createElement('span');
                    removeBtn.className = 'ms-1 cursor-pointer';
                    removeBtn.innerHTML = '&times;';
                    removeBtn.onclick = () => {
                        selectedRegions.delete(code);
                        updateSelectedRegionsDisplay();
                        updateRegionCodes();
                        displayAllRegions();
                    };
                    
                    badge.appendChild(removeBtn);
                    selectedRegionsContainer.appendChild(badge);
                }
            });
        }

        function updateRegionCodes() {
            regionCodesInput.value = JSON.stringify(Array.from(selectedRegions));
        }
    }

    function formatPhoneNumber(input) {
        const placeholder = '+7 (___) ___-__-__';
        
        input.placeholder = placeholder;

        input.addEventListener('focus', function() {
            if (this.value === '') {
                this.value = '+7 (';
            }
        });

        input.addEventListener('input', function(e) {
            let