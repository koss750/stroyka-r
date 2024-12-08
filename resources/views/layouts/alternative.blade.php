<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (env('TEST_ENVIRONMENT') != 'true')
    <!-- Yandex.Metrika counter -->
    <script data-cfasync="false" type="text/javascript">
        setTimeout(function(){
            (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

       ym(97430601, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true
       });
        }, 0); //set this as high as you can without ruining your stats.
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/97430601" style="position:absolute; left:-9999px;" alt=""></div></noscript>
    @endif
    <link rel="icon" type="image/x-icon" href="https://xn--80ardojfh.com/assets/images/logo.ico">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link rel="font" href="https://xn--80ardojfh.com/assets/fonts/font.ttf"/>
    <script src="https://xn--80ardojfh.com/assets/js/jquery.min.js"></script>
    <script src="https://xn--80ardojfh.com/assets/js/typeahead.min.js"></script>
    <script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="https://xn--80ardojfh.com/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ time() }}">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    
    @yield('additional_head')
    @stack('further_head')
</head>
<body>
    @stack('further_body')
    @include('components.top')

    <section id="afterHeader">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="mainHeading">
                    </div>
                </div>
            </div>
            @yield('content')
        </div>
    </section>

    <x-podval />
    @stack('further_scripts')
    <script src="https://xn--80ardojfh.com/assets/js/jquery.min.js"></script>
    <script src="https://xn--80ardojfh.com/assets/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/custom.js') }}?v={{ time() }}"></script>
    
    <!-- /Yandex.Metrika counter -->
    @yield('additional_scripts')
</body>
@stack('further_styles')
</html>
