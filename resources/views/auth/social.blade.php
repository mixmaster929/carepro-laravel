@extends('layouts.auth')
@section('page-title',__('site.complete-registration'))

@section('content')

    <div class="az-signin-wrapper">

        <div class="az-card-signin">

            <a  href="{{ route('homepage') }}">
                @if(!empty(setting('image_logo')))
                    <img   class="logo"     src="{{ asset(setting('image_logo')) }}"   >
                @else
                    <h1 class="az-logo">{{ setting('general_site_name') }}</h1>
                @endif
            </a>


            <div class="az-signin-header">
                <h2>@lang('site.complete-registration')</h2>
                <h4>@lang('site.hello') {{ $userProfile->firstName }}</h4>
                @include('partials.flash_message')
                <p>@lang('site.complete-registration-text')</p>

                    <div class="row">
                        @if(setting('general_enable_employer_registration')==1)
                            <div  class="col-md-6 int_tcmb">
                                <i   class="int_fs80 fa fa-user"></i> <br/>
                                <a class="btn btn-primary rounded" href="{{ route('social.employer') }}">@lang('site.i-employer')</a>

                            </div>
                        @endif
                        @if(setting('general_enable_candidate_registration')==1)
                            <div  class="col-md-6 int_tcmb">
                                <i   class="int_fs80 fa fa-user-tie"></i> <br/>
                                <a class="btn btn-success rounded" href="{{ route('social.candidate') }}">@lang('site.i-candidate')</a>



                            </div>
                        @endif

                    </div>


        </div><!-- az-card-signin -->

    </div><!-- az-signin-wrapper -->

@endsection

@section('header')

            <link rel="stylesheet" href="{{ asset('css/admin/confirm.css') }}">

@endsection
