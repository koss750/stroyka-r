<header id="headerBar">
    <div class="container">
        <div class="row align-items-center">
            <div id="logoContainer" class="col-4 col-3-sm">
                <div class="headerLogo">
                    <a href="/">
                        @if (env('TEST_ENVIRONMENT') != 'true')
                            <img src="{{ asset('assets/images/logo5.png') }}" alt="Стройка.com" class="logo-image">
                        @else
                            <p class="test-environment">ТЕСТОВАЯ СРЕДА</p>
                        @endif
                    </a>
                </div>
            </div>
            <div id="searchContainer" class="col-4 col-7-sm">
                <div class="searchBar">
                    <input type="text" id="search-input" placeholder="Поиск по сайту..." autocomplete="off">
                    <div id="autocomplete-results" class="autocomplete-results"></div>
                </div>
            </div>
            <div id="menuContainer" class="col-4 col-2-sm">
                <div class="headerMenu">
                    <ul class="nav">
                        @guest
                            <li><a href="#" class="signup-btn">Регистрация</a></li>
                            <li><a href="../login" class="login-btn">Войти</a></li>
                        @endguest
                        @auth
                            <li class="dropdown">
                                <a href="/my-account" class="dropdown-toggle">Личный Кабинет</a>
                                <div class="dropdown-menu">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Выйти</button>
                                    </form>
                                </div>
                            </li>
                        @endauth
                    </ul>
                </div>
                <div class="mobile-toggle">
                    <i class="fas fa-bars hamburger-icon"></i>
                    <i class="fas fa-search search-icon" style="display: none;"></i>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Login Popup -->
<div id="login-popup" class="popup">
    <div class="popup-content">
        <span class="close">&times;</span>
        <h2 class="mb-4">Вход</h2>
        <form id="login-form">
            @csrf
            <div class="form-group">
                <label for="login-email">Email</label>
                <input type="email" class="form-control no-text-transform" id="login-email" name="email" required>
            </div>
            <div class="form-group">
                <label for="login-password">Пароль</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="login-password" name="password" required>
                    <span class="input-group-text toggle-password" data-target="#login-password">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input custom-checkbox" id="remember-me" name="remember">
                <label class="form-check-label" for="remember-me">Запомнить меня</label>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Войти</button>
        </form>
    </div>
</div>

<!-- Combined Signup Popup -->
<div id="signup-popup" class="popup">
    <div class="popup-content">
        <span class="close">&times;</span>
        <h2 class="mb-4">Регистрация</h2>
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
                    <div class="form-group">
                        <label for="individual-name">Имя</label>
                        <input type="text" class="form-control" id="individual-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="individual-email">E-mail</label>
                        <input type="email" class="form-control no-text-transform" id="individual-email" name="email" required>
                    </div>
                    <div class="input-group">
                        <input type="password" class="form-control" id="individual-password" name="password" required>
                        <span class="input-group-text toggle-password" data-target="#individual-password">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="individual-password-confirmation">Подтвердите пароль</label>
                        <input type="password" class="form-control" id="individual-password-confirmation" name="password_confirmation" required>
                    </div>
                    <div class="form-group">
                        <label for="individual-region-select">Регион</label>
                        <select class="form-control" id="individual-region-select" name="region_code" required>
                            <option value="">Выберите Ваш Регион</option>
                            <!-- Options will be populated dynamically -->
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Зарегистрироваться</button>
                </form>
            </div>
            <div class="tab-pane fade" id="legal" role="tabpanel" aria-labelledby="legal-tab">
                <form id="signup-form-legal">
                    <div class="form-group">
                        <label for="inn">ИНН</label>
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
                        <!-- Additional fields will be populated dynamically -->
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" style="display: none;">Зарегистрироваться</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cache DOM elements
    const signupBtn = document.querySelector('.signup-btn');
    const signupPopup = document.getElementById('signup-popup');
    const closeBtns = document.querySelectorAll('.close');
    const registerTabs = document.querySelectorAll('#registerTabs button');
    const individualForm = document.getElementById('signup-form-individual');
    const legalForm = document.getElementById('signup-form-legal');
    const innInput = document.getElementById('inn');
    const checkInnButton = document.getElementById('check-inn');
    const companyConfirmation = document.getElementById('company-confirmation');
    const foundCompanyName = document.getElementById('found-company-name');
    const confirmCompanyButton = document.getElementById('confirm-company');
    const rejectCompanyButton = document.getElementById('reject-company');
    const additionalFields = document.getElementById('additional-fields');
    const submitButton = legalForm.querySelector('button[type="submit"]');
    const mobileToggle = document.querySelector('.mobile-toggle');
    const hamburgerIcon = document.querySelector('.hamburger-icon');
    const searchIcon = document.querySelector('.search-icon');
    const searchInput = document.querySelector('#search-input');
    const autocompleteResults = document.getElementById('autocomplete-results');
    const searchContainer = document.querySelector('#searchContainer');
    const headerMenu = document.querySelector('.headerMenu');
    const menuContainer = document.querySelector('#menuContainer');

    // Global variables
    let regions = [];
    let selectedRegions = new Set();
    let lastCheckedInn = '';
    let isCompanyActive = false;
    let companyFormData = null;

    const togglePasswordButtons = document.querySelectorAll('.toggle-password');

    togglePasswordButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetInput = document.querySelector(this.getAttribute('data-target'));
            const icon = this.querySelector('i');

            if (targetInput.type === 'password') {
                targetInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                targetInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Event listeners
    if (signupBtn) {
        signupBtn.addEventListener('click', showSignupPopup);
    } else {
        console.warn("Signup button not found in the DOM");
    }
    closeBtns.forEach(btn => btn.addEventListener('click', hideSignupPopup));
    window.addEventListener('click', closePopupOnOutsideClick);
    individualForm.addEventListener('submit', handleIndividualSubmit);
    legalForm.addEventListener('submit', handleLegalSubmit);
    checkInnButton.addEventListener('click', checkInn);
    confirmCompanyButton.addEventListener('click', confirmCompany);
    rejectCompanyButton.addEventListener('click', rejectCompany);

    // Initialize tabs
    initializeTabs();

    // Load regions for individual registration
    loadRegions();

    if (searchInput && autocompleteResults) {
        searchInput.addEventListener('input', function() {
            const searchQuery = this.value.trim();
            if (searchQuery.length >= 2) {
                fetch('/search-designs?query=' + searchQuery, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    const autocompleteItems = data.map(design => {
                        return `<div class="autocomplete-item"><a href="/project/${design.slug}">${design.title}</a></div>`;
                    }).join('');
                    autocompleteResults.innerHTML = autocompleteItems;
                    autocompleteResults.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            } else {
                autocompleteResults.style.display = 'none';
            }
        });

        document.addEventListener('click', function(event) {
            if (!searchInput.contains(event.target) && !autocompleteResults.contains(event.target)) {
                autocompleteResults.style.display = 'none';
            }
        });
    } else {
        console.warn("Search input or autocomplete results element not found");
    }

    function toggleMobileView() {
        hamburgerIcon.style.display = hamburgerIcon.style.display === 'none' ? 'inline-block' : 'none';
        searchIcon.style.display = searchIcon.style.display === 'none' ? 'inline-block' : 'none';
        searchIcon.classList.toggle('col-2-sm');
        searchInput.style.display = searchInput.style.display === 'none' ? 'inline-block' : 'none';
        searchContainer.classList.toggle('col-7-sm');
        searchContainer.classList.toggle('col-2-sm');
        menuContainer.classList.toggle('col-2-sm');
        menuContainer.classList.toggle('col-7-sm');
        headerMenu.style.display = headerMenu.style.display === 'none' ? 'block' : 'none';
    }

    mobileToggle.addEventListener('click', toggleMobileView);

    // Initial setup for mobile view
    function setupMobileView() {
        if (window.innerWidth <= 770) {
            searchContainer.classList.add('col-7-sm');
            searchContainer.classList.remove('col-4');
            menuContainer.classList.add('col-2-sm');
            menuContainer.classList.remove('col-4');
            headerMenu.style.display = 'none';
            mobileToggle.style.display = 'block';
            searchIcon.style.display = 'none';
            searchInput.style.display = 'inline-block';
        } else {
            searchContainer.classList.remove('col-7-sm', 'col-2-sm');
            searchContainer.classList.add('col-4');
            menuContainer.classList.remove('col-2-sm', 'col-7-sm');
            menuContainer.classList.add('col-4');
            headerMenu.style.display = 'block';
            mobileToggle.style.display = 'none';
            hamburgerIcon.style.display = 'inline-block';
            searchIcon.style.display = 'none';
            searchInput.style.display = 'inline-block';
        }
    }

    // Run setup on load and resize
    setupMobileView();

    // Functions
    function showSignupPopup(e) {
        e.preventDefault();
        if (signupPopup) {
            signupPopup.style.display = 'block';
        } else {
            console.warn("Signup popup element not found");
        }
    }

    function hideSignupPopup() {
        signupPopup.style.display = 'none';
    }

    function closePopupOnOutsideClick(event) {
        if (event.target == signupPopup) {
            hideSignupPopup();
        }
    }

    function initializeTabs() {
        registerTabs.forEach(function (triggerEl) {
            if (typeof bootstrap !== 'undefined') {
                var tabTrigger = new bootstrap.Tab(triggerEl);
                triggerEl.addEventListener('click', function (event) {
                    event.preventDefault();
                    tabTrigger.show();
                    if (this.id === 'legal-tab') {
                        initializeRegionSelection();
                    }
                });
            } else {
                console.error('Bootstrap is not defined. Make sure Bootstrap JavaScript is loaded.');
                triggerEl.addEventListener('click', function (event) {
                    event.preventDefault();
                    registerTabs.forEach(tab => {
                        tab.classList.remove('active');
                        document.querySelector(tab.getAttribute('data-bs-target')).classList.remove('show', 'active');
                    });
                    this.classList.add('active');
                    document.querySelector(this.getAttribute('data-bs-target')).classList.add('show', 'active');
                    if (this.id === 'legal-tab') {
                        initializeRegionSelection();
                    }
                });
            }
        });
    }

    function handleIndividualSubmit(e) {
        e.preventDefault();
        const formData = new FormData(this);
        submitForm('/api/register-individual', formData, this);
        alert ("Для подтверждения регистрации вам будет отправлена письмо с ссылкой на подтверждение. Не забудьте проверить папку \"Спам\".");
        window.location.href = '/site';
    }

    function handleLegalSubmit(e) {
        e.preventDefault();
        const formData = new FormData(this);
        submitForm('/api/register-legal-entity', formData, this);
    }

    

    function submitForm(url, formData, form) {
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                form.reset();
                selectedRegions.clear();
                updateSelectedRegionsDisplay();
                hideSignupPopup();
            } else {
                alert('Ошибка при регистрации: ' + (data.message || 'Неизвестная ошибка'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Произошла ошибка при отправке формы');
        });
    }
    function checkInn() {
        if (!{{ config('app.allow_legal_registration') ? 'true' : 'false' }}) {
            alert('Регистрация юр лиц сейчас недоступна');
            return;
        }
        const inn = innInput.value.trim();
        if (inn === lastCheckedInn) return;
        lastCheckedInn = inn;

        fetch(`/api/check-company/${inn}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.company_name) {
                    companyFormData = data;
                    companyFormData.inn = inn;
                    foundCompanyName.textContent = data.company_name;
                    companyConfirmation.style.display = 'block';
                    isCompanyActive = data.is_active;
                } else {
                    companyFormData = null;
                    alert('Компания не найдена или произошла ошибка при проверке ИНН');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                companyFormData = null;
                alert('Произошла ошибка при проверке ИНН');
            });
    }

    function confirmCompany() {
        if (!companyFormData) {
            alert('Пожалуйста, сначала проверьте ИНН компании.');
            return;
        }

        if (!companyFormData.is_active) {
            alert('К сожалению, эта компания не активна. Пожалуйста, обратитесь в службу поддержки.');
            return;
        }

        // Create a form element
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/legal-entity-registration-form';

        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
        form.appendChild(csrfToken);

        // Add company data
        for (const key in companyFormData) {
            if (companyFormData.hasOwnProperty(key)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = companyFormData[key];
                form.appendChild(input);
            }
        }

        // Append form to body and submit
        document.body.appendChild(form);
        form.submit();
    }

    function rejectCompany() {
        companyConfirmation.style.display = 'none';
        additionalFields.style.display = 'none';
        submitButton.style.display = 'none';
        innInput.value = '';
        lastCheckedInn = '';
    }

    function populateFields(inn) {
        fetch(`/api/check-company/${inn}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    additionalFields.innerHTML = generateAdditionalFieldsHTML(data);
                    initializeRegionSelection();
                    formatPhoneNumber(document.getElementById('phone'));
                    formatPhoneNumber(document.getElementById('additional_phone'));
                } else {
                    alert('Ошибка при получении данных компании');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Произошла ошибка при получении данных компании');
            });
    }

    function generateAdditionalFieldsHTML(data) {
        return `
            <div class="form-group">
                <label for="company_name">Название фирмы</label>
                <input type="text" class="form-control" id="company_name" name="company_name" value="${data.company_name}" readonly>
            </div>
            <div class="form-group">
                <label for="kpp">КПП</label>
                <input type="text" class="form-control" id="kpp" name="kpp" value="${data.kpp}" readonly>
            </div>
            <div class="form-group">
                <label for="ogrn">ОГРН</label>
                <input type="text" class="form-control" id="ogrn" name="ogrn" value="${data.ogrn}" readonly>
            </div>
            <div class="form-group">
                <label for="legal_address">ЮР. адрес фирмы</label>
                <input type="text" class="form-control" id="legal_address" name="legal_address" value="${data.address}" readonly>
            </div>
            <div class="form-group">
                <label for="physical_address">ФИЗ. Адрес</label>
                <input type="text" class="form-control no-text-transform" id="physical_address" name="physical_address" required>
            </div>
            <div class="form-group">
                <label for="email">Адрес эл. Почты</label>
                <input type="email" class="form-control no-text-transform" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Номер телефона организации</label>
                <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="additional_phone">Второй контактный номер тел.</label>
                <input type="text" class="form-control" id="additional_phone" name="additional_phone" required>
            </div>
            <div class="form-group">
                <label for="contact_name">Имя контактного лица</label>
                <input type="text" class="form-control" id="contact_name" name="contact_name" required>
            </div>
            <div class="form-group">
                <label for="legal-password">Пароль</label>
                <input type="password" class="form-control" id="legal-password" name="password" required>
            </div>
            <div class="form-group">
                <label for="legal-password-confirmation">Подтвердите пароль</label>
                <input type="password" class="form-control" id="legal-password-confirmation" name="password_confirmation" required>
            </div>
            <div class="form-group">
                <label for="region-input">Поиск регионов</label>
                <input type="text" class="form-control" id="region-input" placeholder="Введите регионы предоставления ваших услуг">
                <div id="region-suggestions" class="region-suggestions"></div>
                <div id="selected-regions" class="mt-2"></div>
                <input type="hidden" id="region-codes" name="region_codes">
            </div>
        `;
    }

    function loadRegions() {
        fetch('/api/regions')
            .then(response => response.json())
            .then(data => {
                regions = data;
                const select = document.getElementById('individual-region-select');
                if (select) {
                    regions.forEach(region => {
                        const option = new Option(`${region.name} (${region.code})`, region.code);
                        select.add(option);
                    });
                }
            })
            .catch(error => console.error('Error loading regions:', error));
    }

    function initializeRegionSelection() {
        const regionInput = document.getElementById('region-input');
        const regionSuggestions = document.getElementById('region-suggestions');
        const selectedRegionsContainer = document.getElementById('selected-regions');
        const regionCodesInput = document.getElementById('region-codes');

        if (!regionInput || !regionSuggestions || !selectedRegionsContainer || !regionCodesInput) {
            console.error('One or more region-related elements not found');
            //return;
        }

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

        displayAllRegions();
    }
});
</script>
<style>
    .test-environment {
        color: #ff6b6b;
        border: 1px solid #ff6b6b;
        border-radius: 5px;
        padding: 5px;
    }
    .input-group-text {
        cursor: pointer;
        margin-bottom: 25px;
    }

    .input-group-text i {
        font-size: 1.2em;
    }
</style>