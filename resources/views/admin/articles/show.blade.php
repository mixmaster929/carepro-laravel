@extends('layouts.admin-page')

@section('pageTitle',__('site.article').' #'.$article->id)
@section('page-title',__('site.article').' #'.$article->id)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >

                        @can('access','view_articles')
                        <a href="{{ url('/admin/articles') }}"  ><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        @endcan

                        @can('access','edit_article')
                        <a href="{{ url('/admin/articles/' . $article->id . '/edit') }}"  ><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>
                        @endcan

                        @can('access','delete_article')
                        <form method="POST" action="{{ url('admin/articles' . '/' . $article->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
                        @endcan

                        <br/>
                        <br/>

                        <ul class="list-group">
                            <li class="list-group-item active">@lang('site.id')</li>
                            <li class="list-group-item">{{ $article->id }}</li>
                            <li class="list-group-item active">@lang('site.menu-title')</li>
                            <li class="list-group-item">{{ $article->menu_title }}</li>
                            <li class="list-group-item active">@lang('site.page-title')</li>
                            <li class="list-group-item">{{ $article->page_title }}</li>
                            <li class="list-group-item active">@lang('site.content')</li>
                            <li class="list-group-item">{!! clean( $article->content ) !!}</li>
                            <li class="list-group-item active">@lang('site.sort-order')</li>
                            <li class="list-group-item">{{ $article->sort_order }}</li>
                            <li class="list-group-item active">@lang('site.meta-title')</li>
                            <li class="list-group-item">{{ $article->meta_title }}</li>
                            <li class="list-group-item active">@lang('site.meta-description')</li>
                            <li class="list-group-item">{{ $article->meta_description }}</li>

                        </ul>



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
