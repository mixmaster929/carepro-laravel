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
        <link href="{{ asset('css/admin.css') }}" rel="stylesheet" />
    <!-- vendor css -->
    <link href="{{ asset('themes/azia/lib/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/azia/lib/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/azia/lib/typicons.font/typicons.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/azia/lib/morris.js/morris.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/azia/lib/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/azia/lib/jqvmap/jqvmap.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('themes/argonpro/assets/vendor/nucleo/css/nucleo.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('themes/argonpro/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" type="text/css">
    
    <link href="{{ asset('themes/argon/assets/js/plugins/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('themes/argonpro/assets/css/argon.min.css?v=1.1.0') }}" type="text/css">

    <!-- azia CSS -->
    <link rel="stylesheet" href="{{ asset('themes/azia/css/azia.css') }}">
    <link href="{{ asset('css/fix.css') }}" rel="stylesheet" />

    @yield('header')
    {!!  setting('general_header_scripts')  !!}
</head>
<body class="az-body az-body-sidebar">

<div class="az-sidebar">
    <div class="az-sidebar-header">
        <a href="{{ url('/') }}" class="az-logo"><span></span>
            @if(!empty(setting('image_logo')))
                <img src="{{ asset(setting('image_logo')) }}" class="navbar-brand-img" >
            @else
                {{ setting('general_site_name') }}
            @endif
        </a>


    </div><!-- az-sidebar-header -->
    <div class="az-sidebar-loggedin">
        <div class="az-img-user online"><img src="{{ userPic(Auth::user()->id) }}" alt=""></div>
        <div class="media-body">
            <h6>{{ Auth::user()->name }}</h6>
            <span>{{ roleName(Auth::user()->role_id) }}</span>
        </div><!-- media-body -->
    </div><!-- az-sidebar-loggedin -->
    <div class="az-sidebar-body">
        <ul class="nav">
            <li class="nav-label">@lang('site.main-menu')</li>
            <li class="nav-item">
                <a href="{{ route('employer.dashboard') }}" class="nav-link"><i class="fa fa-tachometer-alt"></i>&nbsp; @lang('site.dashboard')</a>
            </li><!-- nav-item -->
            <li class="nav-item">
                <a href="#" class="nav-link with-sub"><i class="fa fa-users"></i>&nbsp; @lang('site.orders')</a>
                <ul class="nav-sub">
                    <li class="nav-sub-item"><a href="{{ route('profiles') }}" class="nav-sub-link">@lang('site.browse-profiles')</a></li>
                    <li class="nav-sub-item"><a href="{{ route('order-forms') }}" class="nav-sub-link">@lang('site.create-order')</a></li>
                    <li class="nav-sub-item"><a href="{{ route('employer.orders') }}" class="nav-sub-link">@lang('site.my-orders')</a></li>
                </ul>
            </li><!-- nav-item -->
            <li class="nav-item">
                <a href="#" class="nav-link with-sub"><i class="fa fa-clipboard-list"></i>&nbsp; @lang('site.vacancies')</a>
                <ul class="nav-sub">
                    <li class="nav-sub-item"><a href="{{ route('employer.vacancies.index') }}" class="nav-sub-link">@lang('site.view-vacancies')</a></li>
                    <li class="nav-sub-item"><a href="{{ route('employer.vacancies.create') }}" class="nav-sub-link">@lang('site.create-vacancy')</a></li>
                </ul>
            </li><!-- nav-item -->
            <li class="nav-item">
                <a href="{{ route('user.billing.invoices') }}" class="nav-link"><i class="fa fa-file-invoice-dollar"></i>&nbsp; @lang('site.invoices')</a>
            </li><!-- nav-item -->
            <li class="nav-item">
                <a href="{{ route('employer.placements') }}" class="nav-link"><i class="fa fa-user-tie"></i>&nbsp; @lang('site.placements')</a>
            </li><!-- nav-item -->
            <li class="nav-item">
                <a href="{{ route('employer.interviews') }}" class="nav-link"><i class="fa fa-calendar-alt"></i>&nbsp; @lang('site.interviews')</a>
            </li><!-- nav-item -->

            <li class="nav-item">
                <a href="{{ route('user.contract.index') }}" class="nav-link"><i class="fa fa-handshake"></i>&nbsp; @lang('site.contracts')</a>
            </li><!-- nav-item -->

            <li class="nav-item">
                <a href="#" class="nav-link with-sub"><i class="fa fa-id-card-alt"></i>&nbsp; @lang('site.profile')</a>
                <ul class="nav-sub">
                    <li class="nav-sub-item"><a href="{{ route('employer.profile') }}" class="nav-sub-link">@lang('site.account-details')</a></li>
                    <li class="nav-sub-item"><a href="{{ route('user.password') }}" class="nav-sub-link">@lang('site.change-password')</a></li>
                    <li class="nav-sub-item"><a href="{{ route('user.billing-address.index') }}" class="nav-sub-link">@lang('site.billing-addresses')</a></li>

                </ul>
            </li><!-- nav-item -->


        </ul><!-- nav -->
    </div><!-- az-sidebar-body -->
</div><!-- az-sidebar -->

<div class="az-content az-content-dashboard-two">
    <div class="az-header">
        <div class="container-fluid">
            <div class="az-header-left">
                <a href="#" id="azSidebarToggle" class="az-header-menu-icon"><span></span></a>
            </div><!-- az-header-left -->


            <div class="az-header-right">
                @if(session()->has('cart'))
                <div class="az-header-message">
                    <a title="@lang('site.your-shortlist')" href="{{ route('shortlist') }}"><i class="fa fa-users"></i> <small>@if(is_array(session()->get('cart'))) {{ count(session()->get('cart')) }} @endif</small></a>
                </div>
                @endif
                    @if(session()->has('invoice') && \App\Invoice::find(session()->get('invoice')))
                    <div class="az-header-message">
                        <a href="{{ route('user.invoice.cart') }}"><i class="fa fa-cart-plus"></i> <small>{{ price(\App\Invoice::find(session()->get('invoice'))->amount) }}</small></a>
                    </div>
                    @endif
                <div class="dropdown az-profile-menu">
                    <a href="#" class="az-img-user"><img src="{{ userPic(Auth::user()->id) }}" alt=""></a>
                    <div class="dropdown-menu">
                        <div class="az-dropdown-header d-sm-none">
                            <a href="#" class="az-header-arrow"><i class="icon ion-md-arrow-back"></i></a>
                        </div>
                        <div class="az-header-profile">
                            <div class="az-img-user">
                                <img src="{{ userPic(Auth::user()->id) }}" alt="">
                            </div><!-- az-img-user -->
                            <h6>{{ Auth::user()->name }}</h6>
                            <span>{{ roleName(Auth::user()->role_id) }}</span>
                        </div><!-- az-header-profile -->

                        <a href="{{ route('home') }}" class="dropdown-item"><i class="fa fa-sign-in-alt"></i> @lang('site.dashboard')</a>
                        <a href="{{ route('user.profile') }}" class="dropdown-item"><i class="fa fa-user"></i> @lang('site.my-profile')</a>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" class="dropdown-item"><i class="typcn typcn-power-outline"></i> @lang('site.sign-out')</a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="int_hide">
                            @csrf
                        </form>
                    </div><!-- dropdown-menu -->
                </div>
            </div><!-- az-header-right -->
        </div><!-- container -->
    </div><!-- az-header -->
    <div class="az-content-header d-block d-md-flex">
        <div>
            <h2 class="az-content-title tx-24 mg-b-5 mg-b-lg-8">@yield('page-title')</h2>
            <p class="mg-b-0">@yield('page-subtile')</p>
        </div>
        @hasSection('breadcrumb')
        <div class="az-dashboard-header-right">

                <ol class="breadcrumb breadcrumb-style1 mg-b-0">
                    <li class="breadcrumb-item"><a href="{{ route('employer.dashboard') }}"><i class="fas fa-home"></i></a></li>
                    @yield('breadcrumb')
                </ol>

        </div><!-- az-dashboard-header-right -->
        @endif


    </div><!-- az-content-header -->
    <div class="az-content-body @yield('content-class')">
        @include('partials.flash_message')
        @yield('content')
    </div><!-- az-content-body -->
    <div class="az-footer ht-40">
        <div class="container-fluid pd-t-0-f ht-100p">
            <span>&copy; {{ date('Y') }} {{ setting('general_site_name') }}. All rights reserved. Powered by UXUI.nl</span><p><br>
        </div><!-- container -->
    </div><!-- az-footer -->
</div><!-- az-content -->


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
</body>

</html>
