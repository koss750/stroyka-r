@extends('layouts.alternative')

@section('canonical', '')

@section('additional_head')
<title>Мои переписки</title>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>{{ __('Чертеж фундамента') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="foundation_type">{{ __('Тип фундамента') }}</label>
                            <select id="foundation_type" class="form-control">
                                @foreach($foundationTypes as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="scale">{{ __('Масштаб') }}</label>
                            <select id="scale" class="form-control">
                                @foreach($scales as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="width">{{ __('Ширина') }}</label>
                            <select id="width" class="form-control">
                                @foreach($widths as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="canvas-container" style="border: 1px solid #ccc; margin-bottom: 20px;">
                        <canvas id="drawing-canvas"></canvas>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button id="proceed-btn" class="btn btn-primary float-end">{{ __('Рассчитать') }}</button>
                        </div>
                    </div>

                    <div id="results" class="mt-4" style="display: none;">
                        <h4>{{ __('Результаты') }}</h4>
                        <div class="row">
                            <div class="col-md-3">
                                <p>{{ __('Общая площадь') }}: <span id="total-area">0</span> м²</p>
                            </div>
                            <div class="col-md-3">
                                <p>{{ __('Периметр') }}: <span id="perimeter">0</span> м</p>
                            </div>
                            <div class="col-md-2">
                                <p>{{ __('Углы') }}: <span id="angles">0</span></p>
                            </div>
                            <div class="col-md-2">
                                <p>{{ __('Т-образные') }}: <span id="t-junctions">0</span></p>
                            </div>
                            <div class="col-md-2">
                                <p>{{ __('Х-образные') }}: <span id="x-crossings">0</span></p>
                            </div>
                        </div>
                    </div>
                    <!-- Add this section after the results div -->
@if($userPlans->count() > 0)
<div class="mt-4">
    <h4>{{ __('Недавние планы') }}</h4>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('Тип') }}</th>
                    <th>{{ __('Масштаб') }}</th>
                    <th>{{ __('Площадь') }}</th>
                    <th>{{ __('Дата создания') }}</th>
                    <th>{{ __('Действия') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($userPlans as $plan)
                <tr>
                    <td>{{ $foundationTypes[$plan->type] }}</td>
                    <td>{{ $plan->scale }}</td>
                    <td>{{ $plan->total_area }} м²</td>
                    <td>{{ $plan->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        <button class="btn btn-sm btn-primary load-plan" data-plan-id="{{ $plan->id }}">
                            {{ __('Загрузить') }}
                        </button>
                        <button class="btn btn-sm btn-danger delete-plan" data-plan-id="{{ $plan->id }}">
                            {{ __('Удалить') }}
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script>
let canvas; // Declare canvas in a shared scope

document.addEventListener('DOMContentLoaded', function() {
    // Initialize canvas
    canvas = new fabric.Canvas('drawing-canvas', {
        width: 800,
        height: 600,
        backgroundColor: '#ffffff'
    });

    // Drawing state
    let isDrawing = false;
    let startPoint = null;
    let lines = [];
    
    // Drawing mode
    canvas.isDrawingMode = true;
    canvas.freeDrawingBrush.width = 2;
    canvas.freeDrawingBrush.color = '#000000';

    // Add toolbar buttons
    const toolbar = document.createElement('div');
    toolbar.className = 'drawing-toolbar mb-3';
    toolbar.innerHTML = `
        <button class="btn btn-sm btn-outline-secondary" id="draw-mode">
            <i class="fas fa-pencil-alt"></i> Рисовать
        </button>
        <button class="btn btn-sm btn-outline-secondary" id="select-mode">
            <i class="fas fa-mouse-pointer"></i> Выбрать
        </button>
        <button class="btn btn-sm btn-outline-secondary" id="clear-canvas">
            <i class="fas fa-trash"></i> Очистить
        </button>
    `;
    document.querySelector('.canvas-container').insertAdjacentElement('beforebegin', toolbar);

    // Toolbar event listeners
    document.getElementById('draw-mode').addEventListener('click', function() {
        canvas.isDrawingMode = true;
        this.classList.add('active');
        document.getElementById('select-mode').classList.remove('active');
    });

    document.getElementById('select-mode').addEventListener('click', function() {
        canvas.isDrawingMode = false;
        this.classList.add('active');
        document.getElementById('draw-mode').classList.remove('active');
    });

    document.getElementById('clear-canvas').addEventListener('click', function() {
        if (confirm('Вы уверены, что хотите очистить холст?')) {
            canvas.clear();
            canvas.backgroundColor = '#ffffff';
            canvas.renderAll();
        }
    });

    // Canvas event listeners for line drawing
    canvas.on('mouse:down', function(o) {
        if (!canvas.isDrawingMode) return;
        isDrawing = true;
        let pointer = canvas.getPointer(o.e);
        startPoint = new fabric.Point(pointer.x, pointer.y);
    });

    canvas.on('mouse:move', function(o) {
        if (!isDrawing) return;
        let pointer = canvas.getPointer(o.e);
        if (startPoint) {
            let line = new fabric.Line([
                startPoint.x,
                startPoint.y,
                pointer.x,
                pointer.y
            ], {
                stroke: '#000000',
                strokeWidth: 2,
                selectable: true
            });
            
            if (lines.length > 0) {
                canvas.remove(lines[lines.length - 1]);
            }
            canvas.add(line);
            lines.push(line);
            canvas.renderAll();
        }
    });

    canvas.on('mouse:up', function() {
        isDrawing = false;
        startPoint = null;
        lines = [];
    });

    // Calculation functions
    function calculateTotalArea(canvas) {
        let objects = canvas.getObjects();
        let paths = objects.filter(obj => obj.type === 'path' || obj.type === 'line');
        
        if (paths.length === 0) return 0;

        // Create a polygon from the paths
        let points = [];
        paths.forEach(path => {
            if (path.type === 'line') {
                points.push({ x: path.x1, y: path.y1 });
                points.push({ x: path.x2, y: path.y2 });
            }
        });

        // Remove duplicate points
        points = points.filter((point, index, self) =>
            index === self.findIndex((p) => (
                p.x === point.x && p.y === point.y
            ))
        );

        // Calculate area using the shoelace formula
        let area = 0;
        for (let i = 0; i < points.length; i++) {
            let j = (i + 1) % points.length;
            area += points[i].x * points[j].y;
            area -= points[j].x * points[i].y;
        }
        area = Math.abs(area / 2);

        // Convert area based on scale
        const scale = document.getElementById('scale').value;
        const scaleFactor = parseInt(scale.split(':')[1]);
        return area * (scaleFactor * scaleFactor) / 10000; // Convert to square meters
    }

    function calculatePerimeter(canvas) {
        let objects = canvas.getObjects();
        let paths = objects.filter(obj => obj.type === 'path' || obj.type === 'line');
        let perimeter = 0;

        paths.forEach(path => {
            if (path.type === 'line') {
                let dx = path.x2 - path.x1;
                let dy = path.y2 - path.y1;
                perimeter += Math.sqrt(dx * dx + dy * dy);
            }
        });

        // Convert perimeter based on scale
        const scale = document.getElementById('scale').value;
        const scaleFactor = parseInt(scale.split(':')[1]);
        return perimeter * scaleFactor / 100; // Convert to meters
    }

    function calculateAngles(canvas) {
        let objects = canvas.getObjects();
        let lines = objects.filter(obj => obj.type === 'line');
        let angles = 0;

        lines.forEach((line1, i) => {
            lines.forEach((line2, j) => {
                if (i < j) {
                    // Check if lines intersect
                    let intersects = doLinesIntersect(
                        line1.x1, line1.y1, line1.x2, line1.y2,
                        line2.x1, line2.y1, line2.x2, line2.y2
                    );
                    if (intersects) angles++;
                }
            });
        });

        return angles;
    }

    function calculateTJunctions(canvas) {
        let objects = canvas.getObjects();
        let lines = objects.filter(obj => obj.type === 'line');
        let tJunctions = 0;

        lines.forEach((line1, i) => {
            let intersectCount = 0;
            lines.forEach((line2, j) => {
                if (i !== j) {
                    if (doLinesIntersect(
                        line1.x1, line1.y1, line1.x2, line1.y2,
                        line2.x1, line2.y1, line2.x2, line2.y2
                    )) {
                        intersectCount++;
                    }
                }
            });
            if (intersectCount === 1) tJunctions++;
        });

        return Math.floor(tJunctions / 2); // Divide by 2 as each T-junction is counted twice
    }

    function calculateXCrossings(canvas) {
        let objects = canvas.getObjects();
        let lines = objects.filter(obj => obj.type === 'line');
        let xCrossings = 0;

        lines.forEach((line1, i) => {
            let intersectCount = 0;
            lines.forEach((line2, j) => {
                if (i !== j) {
                    if (doLinesIntersect(
                        line1.x1, line1.y1, line1.x2, line1.y2,
                        line2.x1, line2.y1, line2.x2, line2.y2
                    )) {
                        intersectCount++;
                    }
                }
            });
            if (intersectCount >= 2) xCrossings++;
        });

        return Math.floor(xCrossings / 4); // Divide by 4 as each X-crossing is counted four times
    }

    // Helper function to check if two lines intersect
    function doLinesIntersect(x1, y1, x2, y2, x3, y3, x4, y4) {
        let denominator = ((x2 - x1) * (y4 - y3)) - ((y2 - y1) * (x4 - x3));
        if (denominator === 0) return false;

        let ua = (((x4 - x3) * (y1 - y3)) - ((y4 - y3) * (x1 - x3))) / denominator;
        let ub = (((x2 - x1) * (y1 - y3)) - ((y2 - y1) * (x1 - x3))) / denominator;

        return (ua >= 0 && ua <= 1) && (ub >= 0 && ub <= 1);
    }

    // Update the proceed button click handler
    document.getElementById('proceed-btn').addEventListener('click', function() {
        const drawingData = JSON.stringify(canvas.toJSON());
        const totalArea = calculateTotalArea(canvas);
        const perimeter = calculatePerimeter(canvas);
        const angles = calculateAngles(canvas);
        const tJunctions = calculateTJunctions(canvas);
        const xCrossings = calculateXCrossings(canvas);

        // Display results
        document.getElementById('results').style.display = 'block';
        document.getElementById('total-area').textContent = totalArea.toFixed(2);
        document.getElementById('perimeter').textContent = perimeter.toFixed(2);
        document.getElementById('angles').textContent = angles;
        document.getElementById('t-junctions').textContent = tJunctions;
        document.getElementById('x-crossings').textContent = xCrossings;

        // Save the plan
        saveFoundationPlan({
            type: document.getElementById('foundation_type').value,
            scale: document.getElementById('scale').value,
            width: document.getElementById('width').value,
            drawing_data: drawingData,
            total_area: totalArea,
            perimeter: perimeter,
            angles: angles,
            t_junctions: tJunctions,
            x_crossings: xCrossings
        });
    });

    // Add after canvas initialization
    function createGrid() {
        const gridSize = 20;
        for (let i = 0; i < (canvas.width / gridSize); i++) {
            canvas.add(new fabric.Line([ i * gridSize, 0, i * gridSize, canvas.height], {
                stroke: '#ccc',
                selectable: false
            }));
            canvas.add(new fabric.Line([ 0, i * gridSize, canvas.width, i * gridSize], {
                stroke: '#ccc',
                selectable: false
            }));
        }
    }
    createGrid();

    canvas.on('object:moving', function(options) {
        const gridSize = 20;
        options.target.set({
            left: Math.round(options.target.left / gridSize) * gridSize,
            top: Math.round(options.target.top / gridSize) * gridSize
        });
    });
});
</script>
<script>
// Add these functions to handle loading and deleting plans
document.querySelectorAll('.load-plan').forEach(button => {
    button.addEventListener('click', function() {
        const planId = this.dataset.planId;
        fetch(`/foundation-plan/${planId}`)
            .then(response => response.json())
            .then(data => {
                // Load the plan data into the canvas
                canvas.loadFromJSON(data.drawing_data, function() {
                    canvas.renderAll();
                    // Update form fields
                    document.getElementById('foundation_type').value = data.type;
                    document.getElementById('scale').value = data.scale;
                    document.getElementById('width').value = data.width;
                    // Show results
                    document.getElementById('results').style.display = 'block';
                    document.getElementById('total-area').textContent = data.total_area;
                    document.getElementById('perimeter').textContent = data.perimeter;
                    document.getElementById('angles').textContent = data.angles;
                    document.getElementById('t-junctions').textContent = data.t_junctions;
                    document.getElementById('x-crossings').textContent = data.x_crossings;
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Произошла ошибка при загрузке плана');
            });
    });
});

document.querySelectorAll('.delete-plan').forEach(button => {
    button.addEventListener('click', function() {
        if (confirm('Вы уверены, что хотите удалить этот план?')) {
            const planId = this.dataset.planId;
            fetch(`/foundation-plan/${planId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.closest('tr').remove();
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Произошла ошибка при удалении плана');
            });
        }
    });
});
</script>

@endpush
<style>
.drawing-toolbar {
    margin-bottom: 1rem;
    padding: 0.5rem;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    background-color: #f8f9fa;
}

.drawing-toolbar button {
    margin-right: 0.5rem;
}

.drawing-toolbar button.active {
    background-color: #0d6efd;
    color: white;
}

.canvas-container {
    margin: 1rem 0;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
}
.loading {
    position: relative;
    pointer-events: none;
    opacity: 0.6;
}
.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 2rem;
    height: 2rem;
    margin: -1rem 0 0 -1rem;
    border: 0.25rem solid #f3f3f3;
    border-top: 0.25rem solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
<script>
function saveFoundationPlan(data) {
    fetch('{{ route("foundation.plan.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('План фундамента сохранен успешно');
            // Optionally reload the page to show the new plan in the list
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Произошла ошибка при сохранении плана');
    });
}
</script>
<script>
function getScaleFactor() {
    const scale = document.getElementById('scale').value;
    try {
        return parseInt(scale.split(':')[1]);
    } catch (e) {
        console.error('Invalid scale format:', scale);
        return 100; // default scale factor
    }
}
</script>