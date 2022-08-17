@extends('layouts.auth')
@section('page-title',__('site.confirm-password'))

@section('content')

    <div class="az-signin-wrapper">

        <div class="az-card-signin">

            <a  href="{{ route('homepage') }}">
                @if(!empty(setting('image_logo')))
                    <img   class="img-fluid"    src="{{ asset(setting('image_logo')) }}"   >
                @else
                    <h1 class="az-logo">{{ setting('general_site_name') }}</h1>
                @endif
            </a>


            <div class="az-signin-header">
                <h2>@lang('site.confirm-password')</h2>
                <h4>@lang('site.confirm-password-text')</h4>

                @include('partials.flash_message')

                <form method="post" action="{{ route('password.confirm') }}" >
                    @csrf

                    <div class="form-group">
                        <label for="password" >{{ __('site.password') }}</label>


                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror

                    </div>


                            <button type="submit" class="btn btn-az-primary btn-block">
                                {{ __('site.confirm-password') }}
                            </button>

                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('site.forgot-password') }}
                                </a>
                            @endif


                </form>
            </div><!-- az-signin-header -->

            <div class="az-signin-footer"><br/>
                <p><a href="{{ route('login') }}">@lang('site.login')</a></p>
            </div><!-- az-signin-footer -->

        </div><!-- az-card-signin -->

    </div><!-- az-signin-wrapper -->

@endsection

@section('header')

    <link rel="stylesheet" href="{{ asset('css/admin/confirm.css') }}">

@endsection
