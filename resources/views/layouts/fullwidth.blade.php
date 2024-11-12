
@php
    $controller = App\Http\Controllers\Controller::controller();
    $page = $action = App\Http\Controllers\Controller::action();
    $action = $controller.'_'.$action;
@endphp

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>

    <!-- PAGE TITLE HERE -->
	<title>{{ config('dz.name') }} | @yield('title', $page_title ?? '')</title>

    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	<meta name="robots" content="" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="description" content="@yield('page_description', $page_description ?? '')"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Vora : Crypto Trading UI Admin  Bootstrap 5 Template" />
	<meta property="og:title" content="Vora : Crypto Trading UI Admin Bootstrap 5 & Laravel Template" />
	<meta property="og:description" content="{{ config('dz.name') }} | @yield('title', $page_title ?? '')" />
	<meta property="og:image" content="https://Vora.dexignlab.com/xhtml/social-image.png" />
	<meta name="format-detection" content="telephone=no">

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png')}}">
    
	@if(!empty(config('dz.public.pagelevel.css.'.$action))) 
        @foreach(config('dz.public.pagelevel.css.'.$action) as $style)
            <link href="{{ asset($style) }}" rel="stylesheet" type="text/css"/>
        @endforeach
    @endif

    {{-- Global Theme Styles (used by all pages) --}}
    @if(!empty(config('dz.public.global.css'))) 
        @foreach(config('dz.public.global.css') as $style)
            <link href="{{ asset($style) }}" rel="stylesheet" type="text/css"/>
        @endforeach
    @endif
    <script src="//code.jivosite.com/widget/Sx4EXfaj86" async></script>
</head>

<body class="h-100">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                @yield('content')
            </div>
        </div>
    </div>
</body>
    <!--**********************************
        Scripts
    ***********************************-->
    
    <!-- Required vendors -->
	@if(!empty(config('dz.public.global.js.top')))
        @foreach(config('dz.public.global.js.top') as $script)
            <script src="{{ asset($script) }}" type="text/javascript"></script>
        @endforeach
    @endif
    @if(!empty(config('dz.public.pagelevel.js.'.$action)))
        @foreach(config('dz.public.pagelevel.js.'.$action) as $script)
            <script src="{{ asset($script) }}" type="text/javascript"></script>
        @endforeach
    @endif
    @if(!empty(config('dz.public.global.js.bottom')))
        @foreach(config('dz.public.global.js.bottom') as $script)
            <script src="{{ asset($script) }}" type="text/javascript"></script>
        @endforeach
    @endif

    @stack('scripts')

</html>