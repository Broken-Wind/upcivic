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
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav"><li class="nav-item dropdown">
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        @if(tenant())
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
                        Environment: {{ App::environment() }}
                    </h3>
                </div>
            @endif
            <div class="alert alert-danger text-center" style="display:none;" id="browser-warning">
                <h4>
                    Your browser is incompatible with {{ config('app.name') }}.
                </h4>
                Please download <a href="https://www.google.com/chrome/">Chrome</a>, <a href="https://www.mozilla.org/en-US/firefox/new/">Firefox</a>, or <a href="https://www.microsoft.com/en-us/edge">Edge</a> for the best experience.
            </div>
            @yield('content')
        </main>
    </div>
    <script type="text/javascript">
        {{-- https://stackoverflow.com/a/53149880 --}}
        {{-- TODO: Decide which browsers to support & update isSupported() appropriately --}}
        function get_browser() {
          var ua = navigator.userAgent, tem, M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
          console.log(ua);
          if (/trident/i.test(M[1])) {
            tem = /\brv[ :]+(\d+)/g.exec(ua) || [];
            return { name: 'IE', version: (tem[1] || '') };
          }
          if (M[1] === 'Chrome') {
            tem = ua.match(/\bOPR\/(\d+)/)
            if (tem != null) { return { name: 'Opera', version: tem[1] }; }
          }
          if (window.navigator.userAgent.indexOf("Edge") > -1) {
            tem = ua.match(/\Edge\/(\d+)/)
            if (tem != null) { return { name: 'Edge', version: tem[1] }; }
          }
          M = M[2] ? [M[1], M[2]] : [navigator.appName, navigator.appVersion, '-?'];
          if ((tem = ua.match(/version\/(\d+)/i)) != null) { M.splice(1, 1, tem[1]); }
          return {
            name: M[0],
            version: +M[1]
          };
        }

        var browser = get_browser()
        var isSupported = isSupported(browser);

        function isSupported(browser) {
        //   var supported = false;
        //   if (browser.name === "Chrome" && browser.version >= 48) {
        //     supported = true;
        //   } else if (browser.name === "Edge") {
        //     supported = true;
        //   }
        //   return supported;
        return true;
        }

        if (!isSupported) {
            console.log(browser);
            document.getElementById('browser-warning').style.display = 'block';
        }
      </script>
@endsection
