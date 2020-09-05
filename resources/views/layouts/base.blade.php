<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script src="https://kit.fontawesome.com/2f34de0b7e.js" crossorigin="anonymous"></script>


    <!-- Usersnap for gathering user feedback -->
    <script>
        window.onUsersnapCXLoad = function(api) {
            api.init();
        }
        var script = document.createElement('script');
        script.defer = 1;
        script.src = 'https://widget.usersnap.com/global/load/1c8b695e-895b-434a-8192-e6fd381b0444?onload=onUsersnapCXLoad';
        document.getElementsByTagName('head')[0].appendChild(script);
    </script>

    @yield('head.additional')

</head>
<body>
    @yield('body')
</body>
</html>
