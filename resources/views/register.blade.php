@extends('layouts.alternative')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6">
            <div class="card my-4">
                <div class="card-body">
                    <h2 class="text-center mb-4">{{ __('Регистрация юридического лица') }}</h2>
                    
                    <form id="signup-form-legal">
                        @csrf
                        <div class="mb-3">
                            <label for="inn-legal" class="form-label">ИНН</label>
                            <input type="text" class="form-control" id="inn-legal" name="inn" required>
                            <button type="button" id="check-inn-legal" class="btn btn-secondary mt-2" onclick="checkInn()">Проверить</button>
                        </div>
                        <div id="company-error-legal" style="display: none;">
                            <small class="text-danger">Этот ИНН уже зарегистрирован в системе или некорректен</small>
                        </div>
                        <div id="company-confirmation-legal" style="display: none;">
                            <p>Найдена компания: <span id="found-company-name-legal"></span></p>
                            <p>Это ваша компания?</p>
                            <button type="button" id="confirm-company-legal" class="btn btn-primary" onclick="confirmCompanyLegal()">Да</button>
                            <button type="button" id="reject-company-legal" class="btn btn-secondary">Нет</button>
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

<script>
    let lastCheckedInn = '';
    function handleLegalSubmitLegal(e) {
        e.preventDefault();
        const formData = new FormData(this);
        submitForm('/legal-entity-registration-form-profile', formData, this);
    }

    function checkInn() {
        const companyError = document.getElementById('company-error-legal');
        const innInput = document.getElementById('inn-legal');
        const foundCompanyName = document.getElementById('found-company-name-legal');
        const companyConfirmation = document.getElementById('company-confirmation-legal');
        const inn = innInput.value.trim();
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
                    companyError.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                companyFormData = null;
                companyError.style.display = 'block';
            });
        lastCheckedInn = innInput.value.trim();
    }

    function confirmCompanyLegal() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/legal-entity-registration-form-profile';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
        form.appendChild(csrfToken);

        for (const key in companyFormData) {
            if (companyFormData.hasOwnProperty(key)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = companyFormData[key];
                form.appendChild(input);
            }
        }

        document.body.appendChild(form);
        form.submit();
    }
</script>

<style>
.region-suggestions {
    max-height: 200px;
    overflow-y: auto;
    position: absolute;
    background: white;
    width: 100%;
    z-index: 1000;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}

.region-option {
    padding: 0.5rem;
    border-bottom: 1px solid #eee;
}

.region-option:last-child {
    border-bottom: none;
}

@media (max-width: 576px) {
    .card {
        margin: 0;
        border-radius: 0;
        border-left: none;
        border-right: none;
    }

    .form-control {
        font-size: 16px;
    }
}
</style>
@endsection