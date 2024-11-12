<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление шаблонами</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <meta name="robots" content="noindex, nofollow">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Управление шаблонами</h1>

        <div class="row">
            <!-- Main Template Box -->
            @include('template_box', [
                'template' => $mainTemplate,
                'category' => 'main',
                'title' => 'Главная',
                'name' => 'Главный'
            ])

            <!-- pLenta Template Box -->
            @include('template_box', [
                'template' => $pLenta,
                'category' => 'pLenta',
                'title' => 'Ленточный с плитой',
                'name' => 'Ленточный с плитой'
            ])

            <!-- fLenta Template Box -->
            @include('template_box', [
                'template' => $fLenta,
                'category' => 'fLenta',
                'title' => 'Ленточный фундамент',
                'name' => 'Ленточный фундамент'
            ])

            <!-- plita Template Box -->
            @include('template_box', [
                'template' => $plita,
                'category' => 'plita',
                'title' => 'Расчет плиты',
                'name' => 'Расчет плиты'
            ])

            <!-- srs Template Box -->
            @include('template_box', [
                'template' => $srs,
                'category' => 'srs',
                'title' => 'Свайно-растверковый с плитой перекрытия',
                'name' => 'Свайно-растврковый с плитой перекрытия'
            ])

            <!-- sr Template Box -->
            @include('template_box', [
                'template' => $sr,
                'category' => 'sr',
                'title' => 'Свайно-верковый',
                'name' => 'Свайно-растверковый'
            ])
        </div>

<!-- Main Template Modal -->
<div class="modal fade" id="mainModal" tabindex="-1" role="dialog" aria-labelledby="mainModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mainModalLabel">Сгенерировать смету (Главная)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/external" method="GET">
                <div class="form-group" id="designGroup">
                    <label for="designInput">Название проекта:</label>
                    <input type="text" class="form-control" id="designInput" required>
                    <input type="hidden" name="design" id="designId">
                </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="variant" value="600x300" required>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" name="labour" id="labourCheckbox" checked>
                        <label class="form-check-label" for="labour">Включить цены за работы</label>
                    </div>
                    <div class="form-group">
                        <label>Выберите тип генерации:</label>
                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-outline-primary active">
                                <input type="radio" name="templateType" id="wholeTemplate" value="whole" checked> Целый шаблон
                        </label>
                        <label class="btn btn-outline-primary">
                            <input type="radio" name="templateType" id="singlePage" value="single"> Одну страницу
                        </label>
                    </div>
                </div>
                <div class="form-group" id="sheetnameGroup" style="display: none;">
                    <label for="sheetnameInput">Название листа</label>
                    <input type="text" id="sheetnameInput" class="form-control" list="sheetnameSuggestions">
                    <datalist id="sheetnameSuggestions"></datalist>
                    <input type="hidden" name="sheetname" id="sheetnameHidden" value="all">
                </div>
                    <input type="hidden" id="filenameInput" name="filename" value="{{ $mainTemplate->name }}.xlsx">
                    <input type="hidden" name="debug" value="1"/>
                    <button type="submit" class="btn btn-primary">Сгенерировать</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Template Modals -->
@foreach(['pLenta' => 'lp', 'fLenta' => 'lenta', 'plita' => 'mp', 'srs' => 'srp', 'sr' => 'sr'] as $category => $formType)
<div class="modal fade" id="{{ $category }}Modal" tabindex="-1" role="dialog" aria-labelledby="{{ $category }}ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $category }}ModalLabel">Сгенерировать смету ({{ $category }})</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="dynamic-form" data-category="{{ $category }}">
                    @foreach(collect($formFields[$formType])->sortBy('order') as $field)
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
                                <input type="number" class="form-control light-placeholder" id="{{ $field->name }}" name="{{ $field->name }}" step="0.01" {{ $isRequired }} data-excel-cell="{{ $field->excel_cell }}" placeholder="{{ $field->default }}" {{ $isDisabled }}>
                            @elseif($field->type == 'select')
                                <select class="form-control light-placeholder" id="{{ $field->name }}" name="{{ $field->name }}" {{ $isRequired }} data-excel-cell="{{ $field->excel_cell }}" {{ $isDisabled }}>
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
                    <input type="hidden" name="category" value="{{ $category }}">
                    <input type="hidden" name="debug" value="1"/>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary generate-template">Заполнить шаблон</button>
                        <button type="button" class="btn btn-secondary generate-smeta">Сгенерировать Смету</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

    </div>
</body>
</html>

<script>
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();

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

    $('.dynamic-form').on('click', '.generate-template, .generate-smeta', function(e) {
        e.preventDefault();
        var $form = $(this).closest('form');
        var excelData = {};
        
        $form.find('input, select').each(function() {
            var $input = $(this);
            var excelCell = $input.data('excel-cell');
            if (excelCell) {
                excelData[excelCell] = $input.val();
            }
        });

        var category = $form.data('category');
        var isSmeta = $(this).hasClass('generate-smeta');

        var url = isSmeta ? '{{ route('process-foundation-order') }}' : '{{ route('generate-excel') }}';

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                foundation_type: category,
                excel_data: excelData,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (isSmeta) {
                    if (response.excel_url && response.smeta_url) {
                        window.location.href = response.smeta_url;
                        setTimeout(function() {
                            window.location.href = response.excel_url;
                        }, 1000); // Delay to allow the first download to start
                    } else {
                        alert('Ошибка при генерации файлов.');
                    }
                } else {
                    if (response.download_url) {
                        window.location.href = response.download_url;
                    } else {
                        alert('Ошибка при генерации файла.');
                    }
                }
            },
            error: function() {
                alert('Произошла ошибка при обработке данных.');
            }
        });
    });
});
</script>