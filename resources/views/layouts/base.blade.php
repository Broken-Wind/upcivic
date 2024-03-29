<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name') }} </title>

    <!-- Favicon -->
    <link rel="icon" href="{{ URL::asset('favicon.ico') }}" type="image/x-icon"/>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    @if(App::environment() == 'production')
        <script src="{{ asset('js/mixpanel/init-prod.js') }}" defer></script>
    @else
        <script src="{{ asset('js/mixpanel/init-dev.js') }}" defer></script>
    @endif
    <script src="{{ asset('js/mixpanel/events.js') }}" defer></script>
    <script defer>
        document.addEventListener('DOMContentLoaded', function() {
            mixpanel.register({
                'Active Organization ID': {{ tenant()->organization_id ?? 'null' }},
                'Plan Type': '{{ tenant()->plan_type ?? "null" }}'
            });
        });
    </script>
    <script src="https://kit.fontawesome.com/2f34de0b7e.js" crossorigin="anonymous"></script>
    @stack('scripts')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('css')

</head>
<body>
    @yield('body')
</body>
</html>
