@extends($userLayout)

@section('page-title',__('site.change-password'))

@section('content')
    <form action="{{ route('user.save-password') }}" method="post">
        @csrf
        <div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
            <label for="password" class="control-label">@lang('site.new-password')</label>
            <input class="form-control" name="password" type="password" id="password"  >
            {!! clean( $errors->first('password', '<p class="help-block">:message</p>') ) !!}
        </div>
        <div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
            <label for="password_confirmation" class="control-label">@lang('site.confirm-password')</label>
            <input class="form-control" name="password_confirmation" type="password" id="password_confirmation"   >
            {!! clean( $errors->first('password_confirmation', '<p class="help-block">:message</p>') ) !!}
        </div>

        <input class="btn btn-primary" type="submit" value="{{ __('site.save-changes') }}">
    </form>


@endsection
