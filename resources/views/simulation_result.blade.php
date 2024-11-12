<!-- resources/views/simulation_result.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Bet Simulation Result</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="balanceChart" width="400" height="200"></canvas>
    <script>
        const results = @json($results);
        const ctx = document.getElementById('balanceChart').getContext('2d');
        const data = {
            labels: Array.from({ length: results[0].length }, (_, i) => i + 1),
            datasets: results.map((result, index) => ({
                label: `Simulation ${index + 1}`,
                data: result,
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                tension: 0.1
            }))
        };

        const config = {
            type: 'line',
            data: data,
        };

        new Chart(ctx, config);
    </script>
</body>
</html>
