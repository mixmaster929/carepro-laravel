@extends('layouts.admin-page')

@section('pageTitle',__('site.blog-category').' :'.$blogcategory->id)
@section('page-title',__('site.blog-category').' :'.$blogcategory->id)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >

                        <a href="{{ url('/admin/blog-categories') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        <a href="{{ url('/admin/blog-categories/' . $blogcategory->id . '/edit') }}" ><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>

                        <form method="POST" action="{{ url('admin/blog-categories' . '/' . $blogcategory->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
                        <br/>
                        <br/>

                        <ul class="list-group">
                            <li class="list-group-item active">@lang('site.id')</li>
                            <li class="list-group-item">{{ $blogcategory->id }}</li>
                            <li class="list-group-item active">@lang('site.category')</li>
                            <li class="list-group-item">{{ $blogcategory->category }}</li>
                            <li class="list-group-item active">@lang('site.sort-order')</li>
                            <li class="list-group-item">{{ $blogcategory->sort_order }}</li>

                        </ul>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
