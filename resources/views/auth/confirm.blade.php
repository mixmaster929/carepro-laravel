@extends('layouts.auth')
@section('page-title',__('site.email-confirmation'))

@section('content')

    <div class="az-signin-wrapper">

        <div class="az-card-signin">

            <a  href="{{ route('homepage') }}">
                @if(!empty(setting('image_logo')))
                    <img    class="logo"    src="{{ asset(setting('image_logo')) }}"   >
                @else
                    <h1 class="az-logo">{{ setting('general_site_name') }}</h1>
                @endif
            </a>


            <div class="az-signin-header">
                <h2>@lang('site.email-confirmation')</h2>
                <h4>@lang('site.confirm-your-email')</h4>
                @include('partials.flash_message')
                <p>@lang('site.email-confirmation-text')</p>
            </div><!-- az-signin-header -->

            <div class="az-signin-footer"><br/>
                <p><a class="btn btn-primary" href="{{ route('homepage') }}"><i class="fa fa-home"></i> @lang('site.home')</a></p>
            </div><!-- az-signin-footer -->

        </div><!-- az-card-signin -->

    </div><!-- az-signin-wrapper -->

@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('css/admin/confirm.css') }}">
@endsection
