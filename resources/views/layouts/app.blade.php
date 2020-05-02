<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    {{-- 각 view에서 jquery(javascript) 사용을 위해 하단으로 옮겼다. --}}
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>

    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar fixed-top navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @can('isAdmin') <!-- for ACL admin -->
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                CreateProduct <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('create.showMachineForm') }}">Machine</a>
                                <a class="dropdown-item" href="{{ route('create.showDepartmentForm') }}">Department</a>
                                <a class="dropdown-item" href="{{ route('create.showShiftForm') }}">ShiftWork</a>
                                <a class="dropdown-item" href="{{ route('create.showProductnameForm') }}">ProductName</a>
                                <a class="dropdown-item" href="{{ route('create.showProductForm') }}">Product</a>
                                <a class="dropdown-item" href="{{ route('product.showCreateFeederForm') }}">Feeder</a>
                                <a class="dropdown-item" href="{{ route('create.partForm') }}">Part</a>
                            </div>
                        </li>
                        @endcan <!-- end for ACL admin -->
                        @canany(['isAdmin','isEditor'])<!-- for ACL admin or editor -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('product.select') }}">Product</a>
                        </li>
                        @endcanany<!-- end for ACL admin or editor -->
                        @can('isAdmin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.index') }}">UserProfile</a>
                        </li>
                        @endcan
                        @can('isEditor')<!-- for ACL editor -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('form.order') }}">CreateOrder</a>
                        </li>
                        @endcan<!-- end for ACL editor -->
                        @cannot(['isAdmin'])
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('ask.show.orders') }}">RefillParts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('part.showReadingForm') }}">CheckAll</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="{{ route('part.selectLogsForm') }}">LogFiles</a>
                        </li>
                        @endcannot
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
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

    {{-- 아래는 ACL을 위해 추가. --}}
    @auth
    <script>
        window.user = @json(auth()->user())
    </script>
    @endauth

    {{-- 각 view에서 jquery(javascript) 사용을 위해 이곳으로 옮겼다. --}}
    <script src="{{ asset('js/app.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>

    {{-- 매우중요==>view에서 juery(javascript)사용을 위해...  --}}
    @yield('extra-js')
</body>
</html>
