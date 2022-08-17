<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        @yield('pageTitle',__('saas.account'))
    </title>
    <!-- Favicon -->

    @if(!empty(setting('image_icon')))
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset(setting('image_icon')) }}">
        @endif
                <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
        <!-- Icons -->
        <link href="{{ asset('themes/argon/assets/js/plugins/nucleo/css/nucleo.css') }}" rel="stylesheet" />
        <link href="{{ asset('themes/argon/assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet" />
        <!-- CSS Files -->
        <link href="{{ asset('themes/argon/assets/css/argon-dashboard.css?v=1.1.0') }}" rel="stylesheet" />
        <link href="{{ asset('css/fix.css') }}" rel="stylesheet" />

    @yield('header')
    @if(false)
        {!!  setting('general_header_scripts')  !!}
        @endif
</head>

<body class="">
<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="{{ url('/') }}">
            @if(!empty(setting('image_logo')))
                <img src="{{ asset(setting('image_logo')) }}" class="navbar-brand-img" >
            @else
                <h1>{{ setting('general_site_name') }}</h1>
            @endif
        </a>



        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            @if(session()->has('invoice') && \App\Models\Invoice::find(session()->get('invoice')))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.invoice.cart') }}" role="button" >
                <div class="media align-items-center">
              <span class="  rounded-circle">
                <i class="ni ni-cart text-primary"></i> {{ price(\App\Models\Invoice::find(session()->get('invoice'))->amount) }}
              </span>
                </div>
            </a>
        </li>
            @endif


            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
              <span class="avatar avatar-sm rounded-circle">
                <img   src="{{ asset('img/man.jpg') }}">
              </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">@lang('saas.welcome')!</h6>
                    </div>
                    <a href="{{ route('user.profile') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>@lang('saas.my-profile')</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <a  href="#"
                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" class="dropdown-item">
                        <i class="ni ni-user-run"></i>
                        <span>@lang('saas.logout')</span>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="int_hide">
                            @csrf
                        </form>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="{{ url('/') }}">

                            @if(!empty(setting('image_logo')))
                                <img src="{{ asset(setting('image_logo')) }}"   >
                            @else
                                <h1>{{ setting('general_site_name') }}</h1>
                            @endif
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <ul class="navbar-nav">
                <li class="nav-item  active" >
                    <a class=" nav-link active " href="{{ route('user.dashboard') }}"> <i class="ni ni-tv-2 text-primary"></i> @lang('saas.dashboard')
                    </a>
                </li>
                <li class="nav-item  active" >
                    <a class=" nav-link active " href="{{ route('user.plans') }}"> <i class="ni ni-briefcase-24 text-orange"></i> @lang('saas.subscription-plans')
                    </a>
                </li>
                <li class="nav-item  active" >
                    <a class=" nav-link active " href="{{ route('user.billing.invoices') }}"> <i class="ni ni-money-coins text-yellow"></i> @lang('saas.invoices')
                    </a>
                </li>
                <li class="nav-item  active" >
                    <a class=" nav-link active " href="{{ route('user.stats') }}"> <i class="ni ni-chart-bar-32 text-blue"></i> @lang('saas.stats')
                    </a>
                </li>
                <li class="nav-item  active" >
                    <a class=" nav-link active " href="{{ route('user.domains') }}"> <i class="ni ni-world-2 text-red"></i> @lang('saas.domains')
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link active" href="#nav-settings" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="nav-settings">
                        <i class="ni ni-settings-gear-65 text-default"></i>
                        <span class="nav-link-text">@lang('saas.settings')</span>
                    </a>
                    <div class="collapse" id="nav-settings">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('user.profile')  }}" class="nav-link">@lang('saas.profile')</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('user.billing-address.index') }}" class="nav-link">@lang('saas.billing-addresses')</a>
                            </li>

                        </ul>
                    </div>
                </li>






            </ul>

        </div>
    </div>
</nav>
<div class="main-content">
    <!-- Navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="{{ route('user.dashboard') }}">@yield('pageTitle','Admin')</a>
            @yield('search-form')

            <!-- User -->
            <ul class="navbar-nav align-items-center d-none d-md-flex">


                @if(session()->has('invoice') && \App\Models\Invoice::find(session()->get('invoice')))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.invoice.cart') }}" role="button" >
                            <div class="media align-items-center">
              <span class="  rounded-circle">
                <i class="ni ni-cart text-yellow"></i> {{ price(\App\Models\Invoice::find(session()->get('invoice'))->amount) }}
              </span>
                            </div>
                        </a>
                    </li>
                @endif


                <li class="nav-item dropdown">
                    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media align-items-center">
                <span class="avatar avatar-sm rounded-circle">
                    <img   src="{{ asset('img/man.jpg') }}">
                </span>
                            <div class="media-body ml-2 d-none d-lg-block">
                                <span class="mb-0 text-sm  font-weight-bold">{{ \Illuminate\Support\Facades\Auth::user()->name }}</span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                        <div class=" dropdown-header noti-title">
                            <h6 class="text-overflow m-0">@lang('saas.welcome')!</h6>
                        </div>
                        <a href="{{ route('user.profile') }}" class="dropdown-item">
                            <i class="ni ni-single-02"></i>
                            <span>@lang('saas.my-profile')</span>
                        </a>

                        <div class="dropdown-divider"></div>
                        <a  href="#"
                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" class="dropdown-item">
                            <i class="ni ni-user-run"></i>
                            <span>@lang('saas.logout')</span>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="int_hide">
                                @csrf
                            </form>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End Navbar -->
    <!-- Header -->
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">
                <!-- Card stats -->

                @yield('page-header')

            </div>
        </div>
    </div>
    <div class="container-fluid mt--7">

        @if (count($errors) > 0)
            <div class="int_pldpr50">
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif


        <div class="flash-message int_pldpr50"  >
            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-' . $msg))

                    <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                @endif
            @endforeach
            @if(Session::has('flash_message'))

                <p class="alert alert-success">{{ Session::get('flash_message') }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
            @endif
        </div> <!-- end .flash-message -->
        @yield('content')
        <!-- Footer -->
        <footer class="footer">
            <div class="row align-items-center justify-content-xl-between">
                <div class="col-xl-6">
                    <div class="copyright text-center text-xl-left text-muted">
                        &copy; {{ date('Y') }} {{ setting('general_site_name') }}
                    </div>
                </div>
                <div class="col-xl-6">

                </div>
            </div>
        </footer>
    </div>
</div>

<div class="modal fade"  id="currencyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="currencyModalLabel">@lang('saas.change-currency')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    @foreach(\App\Models\Currency::get() as $currency)

                        <li class="list-group-item"><a href="{{ route('set.currency',['currency'=>$currency->id]) }}">{{$currency->country->currency_name}} ({!! clean( check( $currency->country->currency_code) ) !!})</a></li>

                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('saas.close')</button>
            </div>
        </div>
    </div>
</div>
<!--   Core   -->
<script src="{{ asset('themes/argon/assets/js/plugins/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('themes/argon/assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<!--   Optional JS   -->
<script src="{{ asset('themes/argon/assets/js/plugins/chart.js/dist/Chart.min.js') }}"></script>
<script src="{{ asset('themes/argon/assets/js/plugins/chart.js/dist/Chart.extension.js') }}"></script>
<!--   Argon JS   -->
<script src="{{ asset('themes/argon/assets/js/argon-dashboard.min.js') }}?v=1.1.0"></script>
<script src="{{ asset('js/lib.js') }}" type="text/javascript"></script>
@yield('footer')
@if(false)
{!!  setting('general_footer_scripts')  !!}
    @endif
</body>

</html>
