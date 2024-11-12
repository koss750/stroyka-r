var currentMaterial = null;
var currentTool = 'line';


        
document.getElementById('materialWood').addEventListener('click', function() {
    currentMaterial = 'wood';
    console.log("material chosen");
    // Set properties like color, pattern, etc. for wood
});

document.getElementById('materialBrick').addEventListener('click', function() {
    currentMaterial = 'brick';
    console.log("material chosen");
    // Set properties like color, pattern, etc. for brick
});

document.getElementById('drawRectangle').addEventListener('click', function() {
    currentTool = 'rectangle';
    console.log("tool chosen");
    // Set properties like color, pattern, etc. for wood
});

document.getElementById('drawLine').addEventListener('click', function() {
    currentTool = 'line';
    console.log("tool chosen");
    // Set properties like color, pattern, etc. for brick
});

var startPoint = null;
canvas = new fabric.Canvas('buildingCanvas');

var isDrawing = false;       // Track if we are in drawing mode
var tempShape = null;        // Temporary shape for real-time feedback

canvas.on('mouse:down', function(options) {
    isDrawing = true;
    startPoint = canvas.getPointer(options.e);
    
    if (currentTool === 'rectangle') {
        // Initialize a rectangle with zero width and height
        tempShape = new fabric.Rect({
            left: startPoint.x,
            top: startPoint.y,
            width: 0,
            height: 0,
            fill: 'transparent',
            stroke: 'black',
            strokeWidth: 2
        });
        canvas.add(tempShape);
    }
    else if (currentTool === 'line') {
        // Initialize a line with the same start and end point
        tempShape = new fabric.Line([startPoint.x, startPoint.y, startPoint.x, startPoint.y], {
            stroke: 'black',
            strokeWidth: 2
        });
        canvas.add(tempShape);
    }
});

canvas.on('mouse:move', function(options) {
    if (!isDrawing) return;

    var currentPoint = canvas.getPointer(options.e);

    if (currentTool === 'rectangle') {
        // Update rectangle's width and height based on mouse movement
        tempShape.set({
            width: currentPoint.x - startPoint.x,
            height: currentPoint.y - startPoint.y
        });
    }
    else if (currentTool === 'line') {
        // Update line's end point based on mouse movement
        tempShape.set({ x2: currentPoint.x, y2: currentPoint.y });
    }

    canvas.renderAll();   // Re-render the canvas to show changes
});

canvas.on('mouse:up', function(options) {
    var endPoint = canvas.getPointer(options.e);
    
    if (currentTool === 'rectangle') {
        // ... Rectangle drawing logic ...
    }
    else if (currentTool === 'line') {
        var line = new fabric.Line([startPoint.x, startPoint.y, endPoint.x, endPoint.y], {
            stroke: 'black',    // You can modify this based on the material
            strokeWidth: 2
        });
        canvas.add(line);

        // Calculate line length in pixels
        var lineLengthPixels = Math.sqrt(Math.pow((endPoint.x - startPoint.x), 2) + Math.pow((endPoint.y - startPoint.y), 2));
        
        // Convert to meters
        var lineLengthMeters = lineLengthPixels / 50;

        // Create label text
        var labelText = `${currentMaterial} - ${lineLengthMeters.toFixed(2)}m`;

        // Determine position for the label (placing it in the middle of the line for simplicity)
        var labelPosition = {
            x: (startPoint.x + endPoint.x) / 2,
            y: (startPoint.y + endPoint.y) / 2
        };

        var text = new fabric.Text(labelText, {
            left: labelPosition.x,
            top: labelPosition.y,
            fontSize: 12,
            backgroundColor: 'white'
        });
        canvas.add(text);
    }

    startPoint = null; // Reset the start point
});
