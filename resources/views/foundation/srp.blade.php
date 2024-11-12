@extends('layouts.alternative')

@section('title', 'Калькулятор Ленточного Армированного Фундамента')
@section('description', 'Рассчитайте стоимость ленточного армированного фундамента')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <h2>Калькулятор Ленточного Армированного Фундамента (SRP)</h2>
        <form id="srpFoundationForm" class="mt-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Наличие горизонтальной гидроизоляции плиты перекрытия"></i>
                            Горизонтальная гидроизоляция плиты перекрытия
                        </label>
                        <select class="form-control" name="horizontalWaterproofing" required>
                            <option value="0">Нет</option>
                            <option value="1">Да</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Наличие отмостки"></i>
                            Отмосток
                        </label>
                        <select class="form-control" name="blind" required>
                            <option value="0">Нет</option>
                            <option value="1">Да</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Материал основания отмостка"></i>
                            Основание отмостка
                        </label>
                        <select class="form-control" name="blindBase" required>
                            <option value="0">Щебень</option>
                            <option value="1">Песок</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="blindWidth">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Ширина отмостка в метрах"></i>
                            Ширина отмостка (м)
                        </label>
                        <input type="number" class="form-control" id="blindWidth" name="blindWidth" step="0.1" required>
                    </div>
                    <div class="form-group">
                        <label for="slabThickness">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Толщина плиты перекрытия в метрах"></i>
                            Толщина плиты перекрытия (м)
                        </label>
                        <input type="number" class="form-control" id="slabThickness" name="slabThickness" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="slabArea">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Площадь плиты перекрытия в квадратных метрах"></i>
                            Площадь плиты перекрытия (м2)
                        </label>
                        <input type="number" class="form-control" id="slabArea" name="slabArea" step="0.01" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="outerLength">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Длина внешней ленты фундамента по внешнему габариту"></i>
                            Длина ленты внешней (м)
                        </label>
                        <input type="number" class="form-control" id="outerLength" name="outerLength" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="outerWidth">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Ширина внешней ленты фундамента"></i>
                            Ширина ленты внешней (м)
                        </label>
                        <input type="number" class="form-control" id="outerWidth" name="outerWidth" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="innerLength">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Длина внутренней ленты фундамента"></i>
                            Длина ленты внутренней (м)
                        </label>
                        <input type="number" class="form-control" id="innerLength" name="innerLength" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="innerWidth">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Ширина внутренней ленты фундамента"></i>
                            Ширина ленты внутренней (м)
                        </label>
                        <input type="number" class="form-control" id="innerWidth" name="innerWidth" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="plinthHeight">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Высота цоколя от верхней точки грунта"></i>
                            Высота цоколя (м)
                        </label>
                        <input type="number" class="form-control" id="plinthHeight" name="plinthHeight" step="0.01" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="crossIntersections">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Количество крестообразных пересечений"></i>
                            Количество пересечений крест (шт)
                        </label>
                        <input type="number" class="form-control" id="crossIntersections" name="crossIntersections" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="tIntersections">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Количество Т-образных пересечений"></i>
                            Количество пересечений Т-обр (шт)
                        </label>
                        <input type="number" class="form-control" id="tIntersections" name="tIntersections" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="corners90">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Количество углов 90 градусов"></i>
                            Количество углов 90 гр (шт)
                        </label>
                        <input type="number" class="form-control" id="corners90" name="corners90" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="corners45">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Количество углов 45 градусов"></i>
                            Количество углов 45 гр (шт)
                        </label>
                        <input type="number" class="form-control" id="corners45" name="corners45" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="innerCornersBlind">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Количество внутренних углов при отмостке"></i>
                            Количество внутренних углов при отмостке (шт)
                        </label>
                        <input type="number" class="form-control" id="innerCornersBlind" name="innerCornersBlind" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="buildingAreaOverlap">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Перепад по пятну застройки в метрах"></i>
                            Перепад по пятну застройки (м)
                        </label>
                        <input type="number" class="form-control" id="buildingAreaOverlap" name="buildingAreaOverlap" step="0.01" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="sandBaseThickness">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Толщина песчаного основания в метрах"></i>
                            Толщина песчаного основания (м)
                        </label>
                        <input type="number" class="form-control" id="sandBaseThickness" name="sandBaseThickness" step="0.01" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="crushedStoneBaseThickness">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Толщина щебеночного основания в метрах"></i>
                            Толщина щебеночного основания (м)
                        </label>
                        <input type="number" class="form-control" id="crushedStoneBaseThickness" name="crushedStoneBaseThickness" step="0.01" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="buildingAreaSize">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Площадь пятна застройки в квадратных метрах"></i>
                            S пятна застройки (м2)
                        </label>
                        <input type="number" class="form-control" id="buildingAreaSize" name="buildingAreaSize" step="0.01" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="pileCount">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Количество свай"></i>
                            Количество свай (шт)
                        </label>
                        <input type="number" class="form-control" id="pileCount" name="pileCount" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="pileLength">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Длина свай в метрах"></i>
                            Длина свай (м)
                        </label>
                        <input type="number" class="form-control" id="pileLength" name="pileLength" step="0.1" required>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Рассчитать стоимость</button>
        </form>
    </div>
</div>
@endsection

@section('additional_scripts')
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();

        $('#srpFoundationForm').on('submit', function(e) {
            e.preventDefault();
            // Here you can add the logic to calculate the cost based on the form inputs
            // and send the data to the server for processing
            alert('Форма отправлена. Здесь будет расчет стоимости.');
        });
    });
</script>
@endsection