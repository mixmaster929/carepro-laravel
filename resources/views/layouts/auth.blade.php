<!DOCTYPE html>
<html lang="en">
<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @if(!empty(setting('image_icon')))
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset(setting('image_icon')) }}">
    @endif

    <title>@yield('page-title',__('site.login-register'))</title>

    <!-- vendor css -->
    <link href="{{ asset('themes/azia/lib/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/azia/lib/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/azia/lib/typicons.font/typicons.css') }}" rel="stylesheet">

    <!-- azia CSS -->
    <link rel="stylesheet" href="{{ asset('themes/azia/css/azia.css') }}">
    <link href="{{ asset('css/fix.css') }}" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    @yield('header')

    {!!  setting('general_header_scripts')  !!}
</head>
<body class="az-body">


@yield('content')


<script src="{{ asset('themes/azia/lib/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('themes/azia/lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('themes/azia/lib/ionicons/ionicons.js') }}"></script>

<script src="{{ asset('themes/azia/js/azia.js') }}"></script>

@yield('footer')


{!!  setting('general_footer_scripts')  !!}
</body>

</html>
