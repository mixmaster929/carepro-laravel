@extends('layouts.admin-page')

@section('pageTitle',__('site.vacancy').': '.$vacancy->title)
@section('page-title',__('site.vacancy').': '.$vacancy->title)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >

                        <a href="{{ url('/admin/vacancies') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        <a href="{{ url('/admin/vacancies/' . $vacancy->id . '/edit') }}" ><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>

                        <form method="POST" action="{{ url('admin/vacancies' . '/' . $vacancy->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
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
                            <li class="list-group-item"><ul>
                                    @foreach($vacancy->jobCategories as $category)
                                        <li>{{ $category->name }}</li>
                                    @endforeach
                                </ul></li>

                        </ul>



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
