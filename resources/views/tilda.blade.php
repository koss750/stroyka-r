<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Стройка (версия Гамма)</title>
    <link rel="stylesheet" href="{{ asset('css/fonts-tildasans.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tilda-animation-2.0.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tilda-cards-1.0.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tilda-grid-3.0.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tilda-blocks-page44160913.min.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();
   for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
   k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(97430601, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/97430601" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="full-screen-container">
        <div class="content">
            @include('tilda-body')
        </div>
        <footer class="footer">
        @include('partials.footer')
        </footer>
    </div>
    <script src="{{ asset('js/tilda-animation-2.0.min.js') }}"></script>
    <script src="{{ asset('js/tilda-cards-1.0.min.js') }}"></script>
    <script src="{{ asset('js/tilda-events-1.0.min.js') }}"></script>
    <script src="{{ asset('js/lazyload-1.3.min.export.js') }}"></script>
    <script src="{{ asset('js/tilda-menu-1.0.min.js') }}"></script>
    <script src="{{ asset('js/tilda-polyfill-1.0.min.js') }}"></script>
    <script src="{{ asset('js/tilda-scripts-3.0.min.js') }}"></script>
    <script src="{{ asset('js/tilda-skiplink-1.0.min.js') }}"></script>
    <script src="{{ asset('js/tilda-stat-1.0.min.js') }}"></script>
    <script src="{{ asset('js/tilda-blocks-page44160913.min.js') }}"></script>
</body>
</html>
