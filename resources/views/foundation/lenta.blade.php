@extends('layouts.alternative')

@section('additional_head')
<title>{{ $page_title }}</title>
<meta name="description" content="Калькулятор фундамента. {{ $page_description }}">
<link rel="canonical" href="{{ url()->current() }}" />
@endsection
@section('content')
<div class="row">
@include('partials.tab-navigator', ['items' => [
                ['url' => '/fundament/fundament-lentochnyj', 'label' => 'Ленточный фундамент'],
                ['url' => '/fundament/fundament-svayno-rostverkovyy', 'label' => 'Свайно-ростверковый фундамент'],
                ['url' => '/fundament/fundament-lentochniy-s-plitoy', 'label' => 'Ленточный фундамент с плитой'],
                ['url' => '/fundament/fundament-svayno-rostverkovyy-s-plitoy', 'label' => 'Свайно-ростверковый фундамент с плитой'],
                ['url' => '/fundament/fundament-monolitnaya-plita', 'label' => 'Монолитная плита'],
            ]])
            <h1 class="sr-only">Расчет фундамента</h1>
    <div class="col-sm-12">
        <h2>{{ $page_title }}</h2>
        <form id="stripFoundationForm" class="mt-4">
            @php
                // Sort the formFields array by the 'order' attribute
                $sortedFormFields = $formFields->sortBy('order');
            @endphp
            @foreach($sortedFormFields as $field)
                <div class="form-group" id="group-{{ $field->name }}" 
                     @if($field->depends_on) 
                         data-depends-on="{{ $field->depends_on }}" 
                         data-depends-value="{{ $field->depends_value }}" 
                         style="display: none;" 
                     @endif>
                    <label for="{{ $field->name }}">
                        <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="{{ $field->tooltip }}"></i>
                        {{ $field->label }}
                        @if(strpos($field->validation, 'required') !== false)
                            <span class="text-danger">*</span>
                        @endif
                    </label>
                    @if(!empty($field->image))
                        <img src="{{ $field->image }}" alt="{{ $field->label }} image" class="img-helper">
                    @endif
                    @php
                        $isDisabled = strpos($field->validation, 'disabled') !== false ? 'disabled' : '';
                        $isRequired = strpos($field->validation, 'required') !== false ? 'required' : '';
                    @endphp
                    @if($field->type == 'number')
                        <input type="text" class="form-control light-placeholder" id="{{ $field->name }}" name="{{ $field->name }}" {{ $isRequired }} data-excel-cell="{{ $field->excel_cell }}" placeholder="{{ $field->default }}" {{ $isDisabled }} data-type="number">
                    @elseif($field->type == 'select')
                        <select class="form-control light-placeholder" id="{{ $field->name }}" name="{{ $field->name }}" {{ $isRequired }} data-excel-cell="{{ $field->excel_cell }}" {{ $isDisabled }} data-default="{{ $field->default }}">
                            @php
                                $options = explode(',', $field->options);
                                $firstOption = true;
                            @endphp
                            @foreach($options as $option)
                                @php
                                    list($optionLabel, $optionValue) = explode(':', $option);
                                @endphp
                                <option value="{{ $optionValue }}" {{ $firstOption ? 'selected' : '' }}>{{ $optionLabel }}</option>
                                @php
                                    $firstOption = false;
                                @endphp
                            @endforeach
                        </select>
                    @else
                        <input type="text" class="form-control light-placeholder" id="{{ $field->name }}" name="{{ $field->name }}" {{ $isRequired }} data-excel-cell="{{ $field->excel_cell }}" placeholder="{{ $field->default }}" {{ $isDisabled }}>
                    @endif
                </div>
            @endforeach
            <button type="button" class="btn btn-primary mt-3" data-toggle="modal" data-target="#exampleModal">Пример сметы</button>
            <button type="button" class="btn btn-primary mt-3" data-toggle="modal" data-target="#paymentModal">Купить смету</button>
        </form>
    </div>
</div>

@component('components.payment-modal', ['type' => 'foundation', 'id' => 1, 'title' => $foundation->site_title, 'image' => $foundation->image, 'price' => 0])
@endcomponent
@component('components.payment-modal', ['type' => 'foundation', 'id' => $foundation->id, 'title' => $foundation->site_title, 'image' => $foundation->image, 'price' => 500])
@endcomponent
@endsection

@section('additional_scripts')
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();

        // Button click handlers for opening modals
        $('[data-target="#exampleModal"]').on('click', function() {
            $('#exampleModal').modal('show');
        });

        $('[data-target="#paymentModal"]').on('click', function() {
            $('#paymentModal').modal('show');
        });

        // Handle dependent fields
        $('[data-depends-on]').each(function() {
            var $dependentField = $(this);
            var dependsOn = $dependentField.data('depends-on');
            var dependsValue = $dependentField.data('depends-value');

            $('#' + dependsOn).on('change', function() {
                if ($(this).val() == dependsValue) {
                    $dependentField.show();
                } else {
                    $dependentField.hide();
                }
            }).trigger('change'); // Trigger change to set initial state
        });

        function getFormData(useDefaults = false) {
            var formData = $('#stripFoundationForm').serializeArray();
            var excelData = {};
            
            formData.forEach(function(input) {
                var $input = $('[name="' + input.name + '"]');
                var excelCell = $input.data('excel-cell');
                if (excelCell) {
                    var value;
                    if (useDefaults) {
                        value = $input.attr('placeholder') || $input.data('default') || '';
                    } else {
                        value = input.value;
                    }
                    if ($input.data('type') === 'number') {
                        value = parseFloat(value) || 0;
                    }
                    excelData[excelCell] = value;
                }
            });

            return excelData;
        }

        function sendFoundationData(isExample) {
            var formData = getFormData(isExample);
            var additionalData = isExample ? $('#exampleForm').serializeArray() : $('#paymentForm').serializeArray();
            
            additionalData.forEach(function(input) {
                formData[input.name] = input.value;
            });

            $.ajax({
                url: '/api/foundation/generate-order',
                method: 'POST',
                data: JSON.stringify({
                    foundation_id: {{ $foundation->id }},
                    cellMappings: formData.excel_data,
                    is_example: isExample,
                    user_id: {{ Auth::id() ?? 'null' }} // Add this line
                }),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (isExample) {
                        alert('Настройки фундамента сохранены успешно. Пример сметы будет отправлен на указанный email.');
                    } else {
                        if (response.order_id) {
                            alert('Заказ создан успешно. Номер заказа: ' + response.order_id);
                            // You might want to redirect to an order confirmation page here
                        } else {
                            alert('Ошибка при создании заказа.');
                        }
                    }
                },
                error: function(xhr) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.message 
                        ? xhr.responseJSON.message 
                        : 'Произошла ошибка при обработке данных.';
                    alert(errorMessage);
                }
            });
        }

        $('#exampleForm').on('submit', function(e) {
            e.preventDefault();
            sendFoundationData(true);
        });

        $('#paymentForm').on('submit', function(e) {
            e.preventDefault();
            sendFoundationData(false);
        });

        // Validate number inputs
        $('input[data-type="number"]').on('input', function() {
            this.value = this.value.replace(/[^0-9.,]/g, '');
        });

        // Disable mousewheel on number inputs
        $('input[type="number"]').on('wheel', function(e) {
            e.preventDefault();
        });

        // Disable up and down keys on number inputs
        $('input[type="number"]').on('keydown', function(e) {
            if (e.which === 38 || e.which === 40) {
                e.preventDefault();
            }
        });

        // Card number formatting
        $('#card-number').on('input', function() {
            var target = this;
            var position = target.selectionEnd;
            var length = target.value.length;
            
            target.value = target.value.replace(/[^\d]/g, '').replace(/(.{4})/g, '$1 ').trim();
            target.selectionEnd = position += ((target.value.charAt(position - 1) === ' ' && target.value.charAt(length - 1) === ' ' && length !== target.value.length) ? 1 : 0);
        });

        // Expiry date formatting
        $('#expiry-date').on('input', function() {
            var target = this;
            var position = target.selectionEnd;
            var length = target.value.length;
            
            target.value = target.value.replace(/[^\d]/g, '').substring(0, 4);
            if (target.value.length > 2) {
                target.value = target.value.substring(0, 2) + '/' + target.value.substring(2);
            }
            
            if (length !== target.value.length) {
                position = target.value.length;
            }
            target.selectionEnd = position;
        });
    });
</script>
@endsection

@section('additional_styles')
<style>
    /* Remove spinner from number inputs */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type="number"] {
        -moz-appearance: textfield;
        appearance: textfield;
    }
    /* Additional styles to override browser defaults */
    input[type="number"] {
        padding-right: 0 !important;
    }
</style>
@endsection