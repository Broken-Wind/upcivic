@extends('layouts.base')
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

                            <li class="nav-item">
                                <a class="nav-link" href="{{ tenant()->route('tenant:admin.organizations.index') }}">Organizations</a>
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

                                    @forelse(Auth::user()->tenants as $tenant)

                                        <a class="dropdown-item" href="{{ route('tenant:admin.edit', ['tenant' => $tenant['slug']]) }}">
                                            {{ $tenant['name'] }}
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

            @yield('content')
        </main>
    </div>
@endsection
