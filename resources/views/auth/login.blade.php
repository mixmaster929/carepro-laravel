@extends('layouts.auth')

@section('content')

    <div class="az-signin-wrapper">

        <div class="az-card-signin">

            <div class="row">
                <div    @if($enableRegistration) class="col-md-6" @else class="col-md-12"  @endif>
                    <a  href="{{ route('homepage') }}">
                        @if(!empty(setting('image_logo')))
                            <img  class="logo"   src="{{ asset(setting('image_logo')) }}"   >
                        @else
                            <h1 class="az-logo">{{ setting('general_site_name') }}</h1>
                        @endif
                    </a>


                    <div class="az-signin-header">
                        <h2>@lang('site.login')</h2>
                        <h4>@lang('site.please-login')</h4>
                        @include('partials.flash_message')

                        <div class="row"  >
                            @if(setting('social_enable_facebook')==1)
                            <div class="col-md-6 int_tpmb" >
                                <a  class="int_mt0p btn btn-az-primary btn-sm btn-block  rounded" href="{{ route('social.login',['network'=>'facebook']) }}"><i class="fab fa-facebook-square"></i> @lang('site.login-facebook')</a>
                            </div>
                            @endif

                            @if(setting('social_enable_google')==1)
                            <div class="col-md-6">
                                <a  class="int_mt0p btn btn-danger  btn-sm  btn-block rounded" href="{{ route('social.login',['network'=>'google']) }}"><i class="fab fa-google"></i> @lang('site.login-google')</a>

                            </div>
                            @endif

                        </div>

                        <form method="post" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <label>@lang('site.email')</label>
                                <input type="text" class="form-control @error('email') is-invalid @enderror"  name="email"  required autocomplete="email" autofocus placeholder="@lang('site.enter-email')" value="{{ old('email') }}">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div><!-- form-group -->
                            <div class="form-group">
                                <label>@lang('site.password')</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="@lang('site.enter-password')">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div><!-- form-group -->
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember" >
                                    @lang('site.remember-me')
                                </label>
                            </div>

                            <button class="btn btn-az-primary btn-block">@lang('site.sign-in')</button>
                        </form>
                    </div><!-- az-signin-header -->
                    @if (Route::has('password.request'))
                    <div class="az-signin-footer"><br/>
                        <p><a href="{{ route('password.request') }}">@lang('site.forgot-password')</a></p>
                    </div><!-- az-signin-footer -->
                    @endif



                </div>
                @if($enableRegistration)
                <div class="col-md-6">




                    <div class="az-signin-header">
                        <h2 class="int_mt60">@lang('site.register')</h2>
                        <h4>@lang('site.register-help-text')</h4>

                        <div class="row">
                            @if(setting('general_enable_employer_registration')==1)
                            <div class="col-md-6 int_tcmb" >
                                 <i   class="int_fs80 fa fa-user"></i> <br/>
                                <a class="btn btn-primary rounded" href="{{ route('register.employer') }}">@lang('site.i-employer')</a>

                            </div>
                            @endif
                            @if(setting('general_enable_candidate_registration')==1)
                            <div class="col-md-6 int_tcmb" >
                                 <i  class="int_fs80 fa fa-user-tie"></i> <br/>
                                <a class="btn btn-success rounded" href="{{ route('register.candidate') }}">@lang('site.i-candidate')</a>



                            </div>
                                @endif

                        </div>
                    </div><!-- az-signin-header -->



                </div>
                    @endif
            </div>



        </div><!-- az-card-signin -->

    </div><!-- az-signin-wrapper -->

@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('css/admin/confirm.css') }}">
    @if($enableRegistration)
        <link rel="stylesheet" href="{{ asset('css/admin/registration.css') }}">

    @endif

    @endsection
