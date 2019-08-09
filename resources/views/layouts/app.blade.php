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
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ tenant() ? tenant()->route('tenant:admin.home') : url('/') }}">
                    {{ tenant() ? tenant()['name'] : config('app.name') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto"><li class="nav-item dropdown">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">

                        @if(tenant())

                            <li class="nav-item">
                                <a class="nav-link" href="{{ tenant()->route('tenant:admin.users.invites.create') }}">Invite Admins</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ tenant()->route('tenant:admin.programs.index') }}">Schedule</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ tenant()->route('tenant:admin.templates.index') }}">Templates</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ tenant()->route('tenant:admin.sites.index') }}">Sites</a>
                            </li>

                        @endif

                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">


                                    @if(tenant())

                                        <a class="dropdown-item" href="{{ tenant()->route('tenant:admin.users.edit') }}">
                                            My Profile
                                        </a>

                                    @endif

                                    @forelse(Auth::user()->organizations as $organization)

                                        <a class="dropdown-item" href="{{ route('tenant:admin.organizations.edit', ['tenant' => $organization['slug']]) }}">
                                            {{ $organization['name'] }}
                                        </a>

                                    @empty

                                    @endforelse

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">

            @if(tenant() && !tenant()->isPublished())


                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8">

                            <div class="alert alert-warning">

                                {{ tenant()['name'] }} is not yet published. If you would like other organizations to be able to find you, please visit your <a href="{{ tenant()->route('tenant:admin.organizations.edit') }}">organization profile</a> to publish it.

                            </div>

                        </div>

                    </div>

                </div>

            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
