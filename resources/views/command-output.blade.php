<!DOCTYPE html>
<html>
<head>
    <title>Reindexing Progress</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .success { color: green; }
        .error { color: red; }
        pre {
            background: #f8f8f8;
            padding: 15px;
            border-radius: 3px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reindexing Progress</h1>
        
        @if($success)
            <h2 class="success">{{ $message }}</h2>
        @else
            <h2 class="error">{{ $message }}</h2>
            @if(isset($error))
                <p class="error">Error: {{ $error }}</p>
            @endif
        @endif

        @if(isset($output))
            <h3>Command Output:</h3>
            <pre>{{ $output }}</pre>
        @endif
    </div>
</body>
</html>