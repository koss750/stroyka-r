@extends('layouts.alternative')

@section('canonical', '')

@section('additional_head')
<title>Страница не найдена</title>
<meta name="description" content="Страница не найдена">
<meta name="robots" content="noindex, nofollow">
@endsection

@section('content')
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .container {
            text-align: center;
        }
        h1 {
            font-size: 6rem;
            margin: 0;
            color: #333;
        }
        p {
            font-size: 1.5rem;
            color: #666;
        }
        a {
            color: #0066cc;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .lang {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <div class="container">

        <div class="lang">
            <h2>404</h2>
            <p>Упс! Страница не найдена.</p>
            <p>Что то пошло не так или ссылка по которой вы перешли больше не существует.</p>
            <p><a href="/site">Вернуться на главную страницу</a></p>
        </div>
    </div>
</body>
</html>