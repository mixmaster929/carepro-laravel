@extends($userLayout)

@section('pageTitle','test'.' #'.$test->id)
@section('page-title','test'.' #'.$test->id)

@section('content')
    <div>
        <a href="{{ url('/employer/tests') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
        <a href="{{ url('/employer/tests/' . $test->id . '/edit') }}" ><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>
        <!-- <form method="POST" action="{{ url('employer/tests' . '/' . $test->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
        </form> -->
        <br/>
        <br/>
        <div class="table-responsive">
            <table class="table">
                <tbody>
                    <tr>
                        <th>@lang('site.id')</th><td>{{ $test->id }}</td>
                    </tr>
                    <tr><th> @lang('site.name') </th><td> {{ $test->name }} </td></tr><tr><th> @lang('site.description') </th><td> {!! clean( $test->description ) !!} </td></tr><tr><th> @lang('site.enabled') </th><td> {{ boolToString($test->status) }} </td></tr>
                    <tr>
                        <th>@lang('site.minutes-allowed')</th><td>{{ $test->minutes }}</td>
                    </tr>
                    <tr>
                        <th>@lang('site.allow-multiple')</th><td>{{ $test->allow_multiple }}</td>
                    </tr>
                    <tr>
                        <th>@lang('site.passmark')</th><td>{{ $test->passmark }}%</td>
                    </tr>
                    <tr>
                        <th>@lang('site.show-result')</th><td>{{ boolToString($test->show_result) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
