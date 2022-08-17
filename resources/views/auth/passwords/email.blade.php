@extends('layouts.auth')
@section('page-title',__('site.reset-password'))

@section('content')

    <div class="az-signin-wrapper">

        <div class="az-card-signin">

            <a  href="{{ route('homepage') }}">
                @if(!empty(setting('image_logo')))
                    <img  class="img-fluid"     src="{{ asset(setting('image_logo')) }}"   >
                @else
                    <h1 class="az-logo">{{ setting('general_site_name') }}</h1>
                @endif
            </a>


            <div class="az-signin-header">
                <h2>@lang('site.reset-password')</h2>
                <h4>@lang('site.reset-your-password')</h4>

                @include('partials.flash_message')

                <form method="post" action="{{ route('password.email') }}" >
                    @csrf
                    <div class="form-group">
                        <label for="email" >@lang('site.email')</label>

                        <div >
                            <input  placeholder="@lang('site.enter-email')" id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>






                    <button type="submit" class="btn btn-az-primary btn-block">{{ __('site.send-password-link') }}</button>
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
