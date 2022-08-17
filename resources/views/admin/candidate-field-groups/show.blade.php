@extends('layouts.admin-page')

@section('pageTitle',__('site.candidate-form'))
@section('page-title',__('site.form-section').': '.$candidatefieldgroup->name)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >

                        <a href="{{ url('/admin/candidate-field-groups') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        <a href="{{ url('/admin/candidate-field-groups/' . $candidatefieldgroup->id . '/edit') }}" ><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>

                        <form method="POST" action="{{ url('admin/candidate-field-groups' . '/' . $candidatefieldgroup->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
                        <br/>
                        <br/>

                        <ul class="list-group">
                            <li class="list-group-item active">@lang('site.id')</li>
                            <li class="list-group-item">{{ $candidatefieldgroup->id }}</li>
                            <li class="list-group-item active">@lang('site.name')</li>
                            <li class="list-group-item">{{ $candidatefieldgroup->name }}</li>
                            <li class="list-group-item active">@lang('site.sort-order')</li>
                            <li class="list-group-item">{{ $candidatefieldgroup->sort_order }}</li>

                            <li class="list-group-item active">@lang('site.show-visible')</li>
                            <li class="list-group-item">{{ boolToString($candidatefieldgroup->visible) }}</li>


                            <li class="list-group-item active">@lang('site.show-public')</li>
                            <li class="list-group-item">{{ boolToString($candidatefieldgroup->public) }}</li>

                            <li class="list-group-item active">@lang('site.show-registration')</li>
                            <li class="list-group-item">{{ boolToString($candidatefieldgroup->registration) }}</li>

                        </ul>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
