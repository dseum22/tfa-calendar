@php
use App\Models\Subject;
use Illuminate\Support\Facades\Route;

$name = Route::currentRouteName();
@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Dennis Eum">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'tfa-calendar') }}</title>

    <!-- fonts -->
    <link href="{{ asset('css/bootstrap-icons.css') }}" rel="stylesheet">

    <!-- css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.4.0/main.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-light fixed-top bg-white shadow-sm">
        <a class="navbar-brand bg-secondary text-light px-2 border-clean" href="{{ url('/dashboard') }}">
            {{ config('app.name', 'tfa-calendar') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse mt-2 mt-md-0" id="navbarSupportedContent">
            @if (Auth::user()->role_id == 3)
            <ul class="navbar-nav mr-auto my-3 my-md-0">
                <div class="row" style="width:500px;">
                    <div class="col input-group">
                        <div class="input-group-prepend">
                            <label class="input-group-text">Subjects</label>
                        </div>
                        <select class="custom-select" id="calendarSubjects">
                            <option selected>General</option>
                            @foreach(Subject::get() as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4" id="spinnerArea" style="padding-top:3px;">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </ul>
            @endif
            <ul class="navbar-nav ml-auto">
                @if ($name != 'settings')
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('settings') }}">
                            {{ __('Settings') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                    </div>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                </li>
                @endif
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </ul>
        </div>
    </nav>

    @yield('content')

    <!-- scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    @yield('scripts')
</body>
<footer class="mt-4 pb-3">
    <p class="text-center text-muted">tfa © <span id="year"></span>, dennis eum</p>
</footer>

</html>