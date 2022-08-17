@extends('layouts.admin-page')

@section('pageTitle','employerField'.' #'.$employerfield->id)
@section('page-title','employerField'.' #'.$employerfield->id)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >

                        <a href="{{ url('/admin/employer-fields') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        <a href="{{ url('/admin/employer-fields/' . $employerfield->id . '/edit') }}" title="@lang('site.edit')"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>

                        <form method="POST" action="{{ url('admin/employer-field-groups' . '/' . $employerfield->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
                        <br/>
                        <br/>

                        <ul class="list-group">
                            <li class="list-group-item active">@lang('site.id')</li>
                            <li class="list-group-item">{{ $employerfield->id }}</li>
                            <li class="list-group-item active">@lang('site.name')</li>
                            <li class="list-group-item">{{ $employerfield->name }}</li>
                            <li class="list-group-item active">@lang('site.sort-order')</li>
                            <li class="list-group-item">{{ $employerfield->sort_order }}</li>
                            <li class="list-group-item active">@lang('site.filter')</li>
                            <li class="list-group-item">{{ $employerfield->filter }}</li>

                        </ul>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
