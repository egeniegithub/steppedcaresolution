<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon icon -->
    <link rel="icon" href="{{asset('assets/images/favicon.ico')}}" type="image/x-icon" />
    <!-- fontawesome icon -->
    <link rel="stylesheet" href="{{asset('assets/fonts/fontawesome/css/fontawesome-all.min.css')}}" />
    <!-- animation css -->
    <link rel="stylesheet" href="{{asset('assets/plugins/animation/css/animate.min.css')}}" />
    <!-- vendor css -->
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}" />

    <!-- ckeditor -->
    <script src="{{asset('assets/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('assets/js/jquery-2.2.4.js')}}"></script>
    <script src="{{asset('assets/js/jquery-ui.js')}}"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    @if(Auth::check())
        @if(Auth::user()->role=="Admin" || Auth::user()->role=="Manager")
            @include('layouts.leftside_navigation')
            @include('layouts.header')
        @else
            @include('layouts.user_header')
        @endif
    @endif

    <!-- [ Main Content ] start -->
    <div class=" @if(request()->segment(1) == "login" || request()->segment(1) =="reset-password" || request()->segment(1) =="forget-password"  ) @else pcoded-main-container @endif
        @if(Auth::check())
            @if(Auth::user()->role=="User")
                new-pcoded-header
            @endif
        @endif"

        id="maindiv">
        @yield('content')
    </div>

    @include('layouts.footer')

@yield('scripts')

</body>

</html>
