@extends($userLayout)

@section('pageTitle',__('site.vacancy').': '.$vacancy->title)
@section('page-title',__('site.vacancy').': '.$vacancy->title)

@section('content')
    <div  >
        <a href="{{ url('/employer/vacancies') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
        <a href="{{ url('/employer/vacancies/' . $vacancy->id . '/edit') }}" ><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>
        <br/>
        <br/>
        <ul class="list-group">
            <li class="list-group-item active">@lang('site.id')</li>
            <li class="list-group-item">{{ $vacancy->id }}</li>
            <li class="list-group-item active">@lang('site.title')</li>
            <li class="list-group-item">{{ $vacancy->title }}</li>
            <li class="list-group-item active">@lang('site.description')</li>
            <li class="list-group-item">{!! clean( check($vacancy->description) ) !!}</li>
            <li class="list-group-item active">@lang('site.closing-date')</li>
            <li class="list-group-item">{{ $vacancy->closes_at }}</li>
            <li class="list-group-item active">@lang('site.active')</li>
            <li class="list-group-item">{{ boolToString($vacancy->active) }}</li>
            <li class="list-group-item active">@lang('site.categories')</li>
            <li class="list-group-item">
                <ul>
                    @foreach($vacancy->jobCategories as $category)
                        <li>{{ $category->name }}</li>
                    @endforeach
                </ul>
            </li>
        </ul>
    </div>
@endsection
