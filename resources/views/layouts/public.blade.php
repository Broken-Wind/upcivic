@extends('layouts.base')
{{--
@push('scripts')
    <!-- Usersnap for gathering user feedback; TODO: Renew subscription. -->
    <script>
        window.onUsersnapCXLoad = function(api) {
            api.init();
        }
        var script = document.createElement('script');
        script.defer = 1;
        script.src = 'https://widget.usersnap.com/global/load/1c8b695e-895b-434a-8192-e6fd381b0444?onload=onUsersnapCXLoad';
        document.getElementsByTagName('head')[0].appendChild(script);
    </script>
@endpush
--}}
@section('body')
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ tenant() ? tenant()->route('tenant:admin.home') : url('/') }}">
                    {{ tenant() ? tenant()['name'] : config('app.name') }}
                </a>
            </nav>
        </div>

        <main class="py-4">
            @if(App::environment() != 'production')
                <div class="alert alert-info text-center">
                    <h3>
                        {{ ucfirst(App::environment()) }}
                    </h3>
                </div>
            @endif
            <div class="alert alert-danger text-center" style="display:none;" id="browser-warning">
                <h4>
                    Your browser is incompatible with {{ config('app.name') }}.
                </h4>
                Please download <a href="https://www.google.com/chrome/">Chrome</a>, <a href="https://www.mozilla.org/en-US/firefox/new/">Firefox</a>, or <a href="https://www.microsoft.com/en-us/edge">Edge</a> and <strong>update to the latest version</strong> for the best experience.
            </div>
            @yield('content')
        </main>
    </div>
    <script type="text/javascript">
        {{-- https://stackoverflow.com/a/53149880 --}}
        function get_browser() {
          var ua = navigator.userAgent, tem, M = ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
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
          var supported = false;
          if (browser.name === "Chrome" && browser.version >= 63) {
            supported = true;
          } else if (browser.name === "Firefox" && browser.version >= 57) {
            supported = true;
          } else if (browser.name === "Edge" && browser.version >= 18) {
            supported = true;
          }
          return supported;
        }

        if (!isSupported) {
            console.log(browser);
            document.getElementById('browser-warning').style.display = 'block';
        }
      </script>
@endsection
