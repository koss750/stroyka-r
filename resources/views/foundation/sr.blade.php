@extends('layouts.alternative')

@section('title', 'Калькулятор Ленточного Фундамента')
@section('description', 'Рассчитайте стоимость ленточного фундамента')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <h2>Калькулятор Ленточного Фундамента (SR)</h2>
        <form id="srFoundationForm" class="mt-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="outerLength">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Общая длина внешнего периметра фундамента по внешнему габариту"></i>
                            Длина ленты внешней (по внешнему габариту) (м)
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
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Общая длина внутренних лент фундамента"></i>
                            Длина ленты внутренней (м)
                        </label>
                        <input type="number" class="form-control" id="innerLength" name="innerLength" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="innerWidth">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Ширина внутренних лент фундамента"></i>
                            Ширина ленты внутренней (м)
                        </label>
                        <input type="number" class="form-control" id="innerWidth" name="innerWidth" step="0.01" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="plinthHeight">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Высота цоколя от верхней точки грунта"></i>
                            Высота цоколя (от верх. точки грунта) (м)
                        </label>
                        <input type="number" class="form-control" id="plinthHeight" name="plinthHeight" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="crossIntersections">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Количество крестообразных пересечений лент"></i>
                            Количество пересечений крест (шт)
                        </label>
                        <input type="number" class="form-control" id="crossIntersections" name="crossIntersections" required>
                    </div>
                    <div class="form-group">
                        <label for="tIntersections">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Количество Т-образных пересечений лент"></i>
                            Количество пересечений Т-обр (шт)
                        </label>
                        <input type="number" class="form-control" id="tIntersections" name="tIntersections" required>
                    </div>
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
                        <label for="buildingAreaOverlap">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Перепад по пятну застройки в метрах"></i>
                            Перепад по пятну застройки (м)
                        </label>
                        <input type="number" class="form-control" id="buildingAreaOverlap" name="buildingAreaOverlap" step="0.01" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="sandBaseThickness">
                            <i class="fas fa-info-circle text-primary mr-1" data-toggle="tooltip" title="Толщина песчаного основания в метрах"></i>
                            Толщина песчаного основания (м)
                        </label>
                        <input type="number" class="form-control" id="sandBaseThickness" name="sandBaseThickness" step="0.01" required>
                    </div>
                </div>
            </div>
            <div class="row">
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
                <div class="col-md-4">
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

        $('#srFoundationForm').on('submit', function(e) {
            e.preventDefault();
            // Here you can add the logic to calculate the cost based on the form inputs
            // and send the data to the server for processing
            alert('Форма отправлена. Здесь будет расчет стоимости.');
        });
    });
</script>
@endsection