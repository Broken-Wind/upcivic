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
                @if(tenant() && Auth::user() && Auth::user()->memberOfTenant(tenant()))
                    @auth
                        @if(!tenant()->isSubscribed())
                            <a href="{{ tenant()->route('tenant:admin.subscriptions.index') }}#availablePlans"><h2><span id="upgradeProBadge" class="badge badge-pill badge-primary">Upgrade to Pro</span></h2></a>
                        @elseif(Auth::user()->onTrial())
                            <a href="{{ tenant()->route('tenant:admin.subscriptions.index') }}#availablePlans"><h4><span id="upgradeProBadge" class="badge badge-pill badge-primary mt-2">Trial ends {{ Auth::user()->trialEndsAt()->diffForHumans() }}</span></h4></a>
                        @endif
                        @if(Auth::user()->canGenerateDemoData())
                            <li class="nav-item">
                            <form method="POST" action="{{ tenant()->route('tenant:admin.demo.store') }}">
                                @csrf
                                <button type="submit" class="btn btn-primary mx-3" onClick="return confirm('Are you sure?')">REGENERATE DEMO DATA</button>
                            </form>
                            </li>
                        @endif
                        <li class="nav-link dropdown">
                            <div class="dropdown-toggle" data-toggle="dropdown" role="button" style="cursor:pointer">
                                Programs
                                <span class="caret"></span>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ tenant()->route('tenant:admin.programs.index') }}">List</a>
                                </li>
                                @if(tenant()->isSubscribed())
                                    <li class="nav-item">
                                        <a class="dropdown-item" href="{{ tenant()->route('tenant:admin.resource_timeline.meetings') }}">Calendar</a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ tenant()->route('tenant:admin.templates.index') }}">Templates</a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-link dropdown">
                            <div class="dropdown-toggle" data-toggle="dropdown" role="button" style="cursor:pointer">
                                Tasks
                                <span class="caret"></span>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li class="nav-item">
                                    <a class="dropdown-item" href="{{ tenant()->route('tenant:admin.assignments.incoming.index') }}">Incoming Assignments</a>
                                </li>
                                @if(tenant()->isSubscribed())
                                    <li class="nav-item">
                                        <a class="dropdown-item" href="{{ tenant()->route('tenant:admin.assignments.outgoing.index') }}">Outgoing Assignments</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="dropdown-item" href="{{ tenant()->route('tenant:admin.tasks.index') }}">Task Templates</a>
                                    </li>
                                @endif
                            </ul>
                        </li>

                        <li class="nav-link dropdown">
                            <div class="dropdown-toggle" data-toggle="dropdown" role="button" style="cursor:pointer">
                                Directory
                                <span class="caret"></span>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a class="dropdown-item" href="{{ tenant()->route('tenant:admin.areas.index') }}">Areas</a>
                            </li>
                            <li>
                                    <a class="dropdown-item" href="{{ tenant()->route('tenant:admin.instructors.index') }}">Instructors</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ tenant()->route('tenant:admin.organizations.index') }}">Organizations</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ tenant()->route('tenant:admin.sites.index') }}">Sites</a>
                                </li>
                            </ul>
                        </li>
                    @endauth
                @endif

                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="dropdown-item" href="{{ route('login') }}">{{ __('Log In') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="dropdown-item" href="{{ route('register') }}">{{ __('Sign Up') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Settings <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            @if(tenant() && Auth::user() && Auth::user()->memberOfTenant(tenant()))
                                <a class="dropdown-item" href="{{ tenant()->route('tenant:admin.edit') }}">
                                    Organization Settings
                                </a>
                                <a class="dropdown-item" href="{{ tenant()->route('tenant:admin.stripe_connect.settings') }}">
                                    Registration Settings
                                </a>
                                <a class="dropdown-item" href="{{ tenant()->route('tenant:admin.users.edit') }}">
                                    Account
                                </a>
                                <a class="dropdown-item" href="{{ tenant()->route('tenant:admin.subscriptions.index') }}">
                                    Available Plans
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