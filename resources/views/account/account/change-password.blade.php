@extends($userLayout)
@section('pageTitle',__('site.change-password'))
@section('content')

    <div class="card-box">
        <form id="form-change-password" role="form" method="POST" action="{{ route('account.update-password') }}" novalidate class="form-horizontal">
            <div class="col-md-9">
                <label for="current-password" class="col-sm-4 control-label">@lang('site.current-password')</label>
                <div class="col-sm-8">
                    <div class="form-group">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="password" class="form-control" id="current-password" name="current-password" placeholder="@lang('site.password')">
                    </div>
                </div>
                <label for="password" class="col-sm-4 control-label">@lang('site.new-password')</label>
                <div class="col-sm-8">
                    <div class="form-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="@lang('site.password')">
                    </div>
                </div>
                <label for="password_confirmation" class="col-sm-4 control-label">@lang('site.reenter-password')</label>
                <div class="col-sm-8">
                    <div class="form-group">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="@lang('site.reenter-password')">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-5 col-sm-6">
                    <button type="submit" class="btn btn-danger">@lang('site.submit')</button>
                </div>
            </div>
        </form>
    </div>

    @endsection
