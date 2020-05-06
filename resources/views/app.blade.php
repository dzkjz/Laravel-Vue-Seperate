<!doctype html>
<html lang="en">
{{--<!-- Global site tag (gtag.js) - Google Analytics -->--}}
{{--<script async src="https://www.googletagmanager.com/gtag/js?id=G-H81K3J5ZGK"></script>--}}
{{--<script>--}}
{{--    window.dataLayer = window.dataLayer || [];--}}

{{--    function gtag() {--}}
{{--        dataLayer.push(arguments);--}}
{{--    }--}}

{{--    gtag('js', new Date());--}}

{{--    gtag('config', 'UA-GA_TRACKING-CODE', {'optimize_id': 'G-H81K3J5ZGK'})--}}
{{--    gtag('config', 'G-H81K3J5ZGK');--}}
{{--</script>--}}
{{--<script>--}}

{{--    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){--}}
{{--        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),--}}
{{--        m=s.getElementsByTagName(o)--}}
{{--            [0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)--}}
{{--    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');--}}

{{--    ga('create', 'G-H81K3J5ZGK', 'auto') ;--}}
{{--    ga('send', 'pageview');--}}

{{--</script>--}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ mix('css/app.css') }}" type="text/css">

    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <title>Document</title>

</head>
<body>
<div id="app">
    <router-view></router-view>
</div>
<script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
<script type="text/javascript"
        src="https://webapi.amap.com/maps?v=1.4.15&key=b1cac56d3a90eebab55af0f46b94437f"></script>
</body>
</html>
