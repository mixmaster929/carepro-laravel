@extends($userLayout)

@section('pageTitle',__('site.edit').' '.__('site.vacancy').': '.$vacancy->title)
@section('page-title',__('site.edit').' '.__('site.vacancy').': '.$vacancy->title)

@section('content')
    <div>
        <div>
            <a href="{{ url('/employer/vacancies') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
            <br />
            <br />
            <form method="POST" action="{{ url('/employer/vacancies/' . $vacancy->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                {{ csrf_field() }}
                @include ('employer.vacancies.form', ['formMode' => 'edit'])
            </form>
        </div>
    </div>
@endsection

@include('employer.vacancies.form-include')