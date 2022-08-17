@extends('layouts.admin-page')

@section('pageTitle',__('site.profile'))
@section('page-title',__('site.profile'))

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >

                        <form method="POST" action="{{ route('admin.save-profile') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">

                            {{ csrf_field() }}

                            <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                                <label for="name" class="control-label">@lang('site.name')</label>
                                <input class="form-control" name="name" type="text" id="name" value="{{ old('name',isset($user->name) ? $user->name : '') }}" >
                                {!! clean( check( $errors->first('name', '<p class="help-block">:message</p>')) ) !!}
                            </div>
                            <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                                <label for="email" class="control-label">@lang('site.email')</label>
                                <input class="form-control" name="email" type="text" id="email" value="{{ old('email',isset($user->email) ? $user->email : '') }}" >
                                {!! clean( check( $errors->first('email', '<p class="help-block">:message</p>')) ) !!}
                            </div>
                            <div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
                                <label for="password" class="control-label"> @lang('site.change') @lang('site.password')</label>
                                <input class="form-control" name="password" type="text" id="password" value="{{ old('password') }}" >
                                {!! clean( check( $errors->first('password', '<p class="help-block">:message</p>')) ) !!}
                            </div>



                            <div class="form-group">
                                <input class="btn btn-primary" type="submit" value="@lang('site.save')">
                            </div>


                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
