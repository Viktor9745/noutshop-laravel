<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}" style='font-weight:bold;'>
                    {{-- {{ config('app.name', 'Laravel') }} --}}NOUTSHOP
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @can('create', App\Models\Item::class)
                        <a class='nav-link text-white' href="{{ route('items.create') }}">{{ __('messages.add_item') }}</a>
                        @endcan
                    </ul>
                    <ul class="navbar-nav me-auto">
                        @can('create', App\Models\Item::class)
                        <a class='nav-link text-white' href="{{ route('adm.categories.index') }}">{{ __('messages.moderator_page') }}</a>
                        @endcan
                    </ul>
                    <ul class="navbar-nav me-auto">
                        @can('viewAny', App\Models\User::class)
                        <a class='nav-link text-white' href="{{ route('adm.users.index') }}">{{ __('messages.admin_page') }}</a>
                        @endcan
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @auth
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('items.cart') }}">{{ __('messages.cart') }}</a>
                        </li>
                        @endauth
                        @guest
                            @if (Route::has('login.form'))
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('login.form') }}">{{ __('messages.login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register.form'))
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('register.form') }}">{{ __('messages.register') }}</a>
                                </li>
                            @endif

                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('messages.logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="post" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            <li class="nav-item">
                                <img class="img-profile rounded-circle" style="width:50px;" src="/storage/userImages/{{Auth::user()->ava}}">
                                    </li>
                        @endguest
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{config('app.languages')[app()->getLocale()]}}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                @foreach(config('app.languages') as $ln => $lang)
                                <a class="dropdown-item" href="{{route('switch.lang', $ln)}}">
                                    {{$lang}}
                                </a>
                                @endforeach
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        @if (session('message'))
            <div class="alert alert-success" role="alert">
                {{ session('message') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            </div>
        @endif
        <div class="container mt-3">
        <div class="row">
            <div class="col-md-3">
                <ul class="list-group">
                    @isset($categories)
                            <a ria-current="true" class="list-group-item active">{{ __('messages.categories') }}</a>
                        @foreach ($categories  as $cat)
                            <a class="list-group-item" href="{{route('items.category', $cat->id)}}">{{$cat->{'name_'.app()->getLocale()} }}</a>
                        @endforeach
                        @endisset
                  </ul>
                  <ul class="list-group">
                    @isset($manufacturers)
                            <a ria-current="true" class="list-group-item active">{{ __('messages.manufacturers') }}</a>
                        @foreach ($manufacturers  as $manufact)
                        <a class="list-group-item" href="{{route('items.manufacturer', $manufact->id)}}">{{$manufact->{'name_'.app()->getLocale()} }}</a>
                        @endforeach
                        @endisset
                  </ul>
            </div>
            @yield('content')
        </div>
    </div>
    </div>
</body>
</html>
