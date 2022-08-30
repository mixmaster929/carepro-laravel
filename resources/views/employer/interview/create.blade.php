@extends($userLayout)

@section('pageTitle',__('site.create-new').' '.__('site.interview'))
@section('page-title',__('site.create-new').' '.__('site.interview'))

@section('content')
    <div>
        <a href="{{ url('employer/application-records/'.$application->vacancy_id) }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
        <br />
        <br />
        <form method="POST" action="{{ url('employer/interview') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
            {{ csrf_field() }}
            @include ('employer.interview.form', ['formMode' => 'create'])
        </form>
    </div>
@endsection
@include('employer.interview.search-script')