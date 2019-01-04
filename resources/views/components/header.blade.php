<header class="app-header">
    <div class="container">
        <div class="app-header__flex">

            <div class="app-header__logo">
                <a href="{{ url('/tours') }}">
                    <img src="{{ asset('/images/viewplex-logo.png') }}">
                </a>
            </div>
            @if (Auth::guest())
            <ul class="app-header__buttons">
                <li><a href="{{ route('login') }}">Login</a></li>
                <li>
                    <a class="btn btn-primary outline white medium" href="{{ route('register') }}">
                        <span class="text">Register</span>
                    </a>
                </li>
            </ul>
            @else

            <div class="app-header__tour-count">

                <a href="{{ url('tours') }}">
                    <i class="material-icons">pageview</i>
                    <?php echo App\Helpers\GlobalData::getRemainingTourCount();  ?>
                </a>

            </div>

            <div class="app-header__user" data-open-menu data-menu-name="user-menu">

                @if (Auth::user()->profile_picture)
                    @php
                    $picture = Storage::url('public/upload/' . Auth::user()->id . '/profile-photo/' . Auth::user()->profile_picture);
                    $picture = 'style="background-image: url(' . $picture . ');"';
                    @endphp
                @else
                    @php($picture = '')
                @endif

                <a class="app-header__user-icon" {!! $picture !!}>

                    @if (!Auth::user()->profile_picture)
                        <i class="material-icons">account_circle</i>
                    @endif

                </a>
                <i class="app-header__dropdown-icon material-icons">keyboard_arrow_down</i>

                <div class="app-header__user-dropdown">

                    <ul>
                        @if (App\Helpers\GlobalData::isFreeSub())
                        <li>
                            <i class="material-icons">add_to_queue</i></i>
                            <a href="{{ url('/user/subscription') }}">Upgrade subscription</a>
                        </li>
                         @endif
                        <li>
                            <i class="material-icons">settings</i>
                            <a href="{{ url('/user/settings') }}">Settings</a>
                        </li>
                        @if (!App\Helpers\GlobalData::isFreeSub())
                        <li>
                            <i class="material-icons">credit_card</i>
                            <a href="{{ url('/user/billing') }}">Billing</a>
                        </li>
                        @endif
                        <li>
                            <i class="material-icons">live_help</i>
                            <a href="{{ url('/user/support') }}">Support</a>
                        </li>
                        <li>
                            <i class="material-icons">live_help</i>
                            <a href="{{ url('/tours') }}">My Tours</a>
                        </li>
                        <li>
                            <i class="material-icons">exit_to_app</i>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();"
                            >
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </div>

            </div>
            @endif

        </div>
    </div>
</header>



{{-- <nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                &nbsp;
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav> --}}