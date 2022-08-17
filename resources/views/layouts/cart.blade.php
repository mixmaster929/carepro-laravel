<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Meta -->
    <meta name="description" content="@yield('meta-description')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="author" content="">

    <title>@yield('page-title',__('site.employer-dashboard'))</title>
    @if(!empty(setting('image_icon')))
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset(setting('image_icon')) }}">
    @endif

<!-- vendor css -->
    <link href="{{ asset('themes/azia/lib/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/azia/lib/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/azia/lib/typicons.font/typicons.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/azia/lib/morris.js/morris.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/azia/lib/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/azia/lib/jqvmap/jqvmap.min.css') }}" rel="stylesheet">

    <!-- azia CSS -->
    <link rel="stylesheet" href="{{ asset('themes/azia/css/azia.css') }}">
    <link href="{{ asset('css/fix.css') }}" rel="stylesheet" />

    @yield('header')
    {!!  setting('general_header_scripts')  !!}
</head>
<body class="az-body az-body-sidebar">
<div class="container">

    <div class="text-center mt-3 mb-3">
        <a href="{{ session()->get('homeUrl') }}" class="az-logo"><span></span>
            @if(!empty(setting('image_logo')))
                <img src="{{ asset(setting('image_logo')) }}" class="navbar-brand-img cart-logo mx-auto d-block" >
            @else
                {{ setting('general_site_name') }}
            @endif
        </a>
        <div>
            <div class="btn-group mt-2 mb-2" role="group" aria-label="Basic example">

                <a onclick="history.back()" href="@section('back')#@show" data-toggle="tooltip" data-placement="top" title="{{ __lang('back') }}" class="btn btn-icon btn-success"><i class="fa fa-chevron-left"></i></a>
                &nbsp;
                <a data-toggle="tooltip" data-placement="top" title="{{ __lang('home') }}"  href="{{ session()->get('homeUrl') }}" class="btn btn-icon btn-primary"><i class="fa fa-home"></i></a>

            </div>

        </div>
    </div>
    <div class="az-content az-content-dashboard-two">

        <div class="az-content-body @yield('content-class')">
            @include('partials.flash_message')
            @yield('content')
        </div><!-- az-content-body -->
        <div class="az-footer ht-40">
            <div class="container-fluid pd-t-0-f ht-100p">
                <span>&copy; {{ date('Y') }} {{ setting('general_site_name') }}</span>
            </div><!-- container -->
        </div><!-- az-footer -->

    </div><!-- az-content -->

</div>



<script src="{{ asset('themes/azia/lib/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('themes/azia/lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('themes/azia/lib/ionicons/ionicons.js') }}"></script>
<script src="{{ asset('themes/azia/lib/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('themes/azia/lib/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('themes/azia/lib/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('themes/azia/lib/jqvmap/maps/jquery.vmap.usa.js') }}"></script>

<script src="{{ asset('themes/azia/js/azia.js') }}"></script>

<script src="{{ asset('js/employer.js') }}"></script>


@yield('footer')
{!!  setting('general_footer_scripts')  !!}
</body>

</html>
