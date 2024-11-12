@extends('layouts.alternative')

@section('title', 'Калькулятор Ленточного Плитного Фундамента')
@section('description', 'Рассчитайте стоимость ленточного плитного фундамента')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <h2>Калькулятор Ленточного Плитного Фундамента (LP)</h2>
        <form id="lpFoundationForm" class="mt-4">
            @foreach($formFields as $field)
                <div class="form-group">
                    <label for="{{ $field->name }}">
                        <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="{{ $field->tooltip }}"></i>
                        {{ $field->label }}
                    </label>
                    @if($field->type == 'select')
                        <select class="form-control" id="{{ $field->name }}" name="{{ $field->name }}" required data-excel-cell="{{ $field->excel_cell }}">
                            @foreach($field->options as $option)
                                <option value="{{ $option->value }}">{{ $option->label }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="{{ $field->type }}" class="form-control" id="{{ $field->name }}" name="{{ $field->name }}" step="{{ $field->step ?? '0.01' }}" required data-excel-cell="{{ $field->excel_cell }}">
                    @endif
                </div>
            @endforeach
            <button type="submit" class="btn btn-primary mt-3">Рассчитать стоимость</button>
        </form>
    </div>
</div>
@endsection

@section('additional_scripts')
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();

        $('#lpFoundationForm').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serializeArray();
            var excelData = {};
            
            formData.forEach(function(input) {
                var $input = $('[name="' + input.name + '"]');
                var excelCell = $input.data('excel-cell');
                if (excelCell) {
                    excelData[excelCell] = input.value;
                }
            });

            $.ajax({
                url: '/process-lp-foundation',
                method: 'POST',
                data: JSON.stringify(excelData),
                contentType: 'application/json',
                success: function(response) {
                    alert('Данные успешно отправлены для обработки.');
                },
                error: function() {
                    alert('Произошла ошибка при отправке данных.');
                }
            });
        });
    });
</script>
@endsection