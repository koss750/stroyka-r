<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $description ?? 'Страница в разработке' }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            text-align: center;
            padding: 40px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 2.5em;
            color: #333;
        }
        p {
            font-size: 1.2em;
            color: #666;
        }
        .construction-icon {
            font-size: 4em;
            color: #f39c12;
            margin-bottom: 20px;
        }
        .btn-home {
            margin-top: 20px;
            font-size: 1em;
            color: white;
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-home:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="construction-icon">
            <i class="fas fa-hard-hat"></i>
        </div>
        <h1>Страница в разработке</h1>
        <p>{{ $description ?? 'Мы активно работаем над этой страницей и планируем запустить ее в ближайшее время. Пожалуйста, проверяйте обновления регулярно. Спасибо за ваше терпение!' }}</p>
        <a href="/" class="btn-home">
            <i class="fas fa-home"></i> Вернуться на главную
        </a>
    </div>
</body>
</html>