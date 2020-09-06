@extends('layouts.base')
@section('head.additional')
    @yield('head.additional')
@endsection
@section('body')
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

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        @if(tenant())
                            @if(Auth::user()->canGenerateDemoData())
                                <li class="nav-item">
                                <form method="POST" action="{{ tenant()->route('tenant:admin.demo.store') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary mr-3" onClick="return confirm('Are you sure?')">REGENERATE DEMO DATA</button>
                                </form>
                                </li>
                            @endif
                            @if(tenant()->isSubscribed())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ tenant()->route('tenant:admin.resource_timeline.index') }}">Schedule</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" href="{{ tenant()->route('tenant:admin.programs.index') }}">Proposals</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ tenant()->route('tenant:admin.templates.index') }}">Programs</a>
                            </li>
                        @endif

                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Log in') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Sign up') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    @forelse(Auth::user()->tenants as $tenant)
                                        <a class="dropdown-item" href="{{ route('tenant:admin.edit', ['tenant' => $tenant['slug']]) }}">
                                            {{ $tenant['name'] }} Settings
                                        </a>
                                    @empty
                                    @endforelse
                                    @if(tenant())
                                        <a class="dropdown-item" href="{{ tenant()->route('tenant:admin.users.edit') }}">
                                            My Profile
                                        </a>
                                    @endif
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
            @if(App::environment() != 'production')
                <div class="alert alert-info text-center">
                    <h3>
                        {{ ucfirst(App::environment()) }}
                    </h3>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
@endsection
