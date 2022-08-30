@extends($userLayout)

@section('pageTitle',__('site.edit').' '.__('site.interview').' #'.$interview->id)
@section('page-title',__('site.edit').' '.__('site.interview').' #'.$interview->id)

@section('content')
    <div  >
        <a href="{{ url('employer/interviews') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
        <br />
        <br />
        <form method="POST" action="{{ url('/employer/interview/update/' . $interview->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
            {{ csrf_field() }}
            @include ('employer.interview.form', ['formMode' => 'edit'])
        </form>
    </div>
@endsection
@include('employer.interview.search-script')