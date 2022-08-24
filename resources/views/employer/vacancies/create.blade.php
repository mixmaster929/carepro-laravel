@extends($userLayout)

@section('pageTitle',__('site.create-new').' '.__('site.vacancy'))
@section('page-title',__('site.create-new').' '.__('site.vacancy'))

@section('content')
    <div>
        <a href="{{ url('/employer/vacancies') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
        <br />
        <br />
        <form method="POST" action="{{ url('/employer/vacancies') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
            {{ csrf_field() }}
            @include ('employer.vacancies.form', ['formMode' => 'create'])
        </form>
    </div>
@endsection


@include('employer.vacancies.form-include')