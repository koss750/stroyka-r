@extends('layouts.alternative')

@section('title', 'Калькулятор Монолитной Плиты')
@section('description', 'Рассчитайте стоимость монолитной плиты фундамента')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <h2>Калькулятор фундамента монолитная плита</h2>
        <form id="mpFoundationForm" class="mt-4">
            @foreach($formFields as $field)
                <div class="form-group">
                    <label for="{{ $field->name }}">
                        <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="{{ $field->tooltip }}"></i>
                        {{ $field->label }}
                        @if($field->validation && strpos($field->validation, 'required') !== false)
                            <span class="text-danger">*</span>
                        @endif
                    </label>
                    @if($field->type == 'number')
                        <input type="number" class="form-control" id="{{ $field->name }}" name="{{ $field->name }}" step="0.01" {{ $field->validation }} placeholder="{{ $field->placeholder }}">
                    @elseif($field->type == 'select')
                        <select class="form-control" id="{{ $field->name }}" name="{{ $field->name }}" {{ $field->validation }}>
                            @foreach(json_decode($field->options) as $option)
                                <option value="{{ $option->value }}">{{ $option->label }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" class="form-control" id="{{ $field->name }}" name="{{ $field->name }}" {{ $field->validation }} placeholder="{{ $field->placeholder }}">
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

    $('#mpFoundationForm').on('submit', function(e) {
        e.preventDefault();
        // Here you can add the logic to calculate the cost based on the form inputs
        // and send the data to the server for processing
        alert('Форма отправлена. Здесь будет расчет стоимости.');
    });
});
</script>
@endsection