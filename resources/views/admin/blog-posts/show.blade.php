@extends('layouts.admin-page')

@section('pageTitle',__('site.blog-post').' :'.$blogpost->title)
@section('page-title',__('site.blog-post').' :'.$blogpost->title)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >
                        @can('access','view_blogs')
                        <a href="{{ url('/admin/blog-posts') }}" ><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        @endcan

                        @can('access','edit_blog')
                        <a href="{{ url('/admin/blog-posts/' . $blogpost->id . '/edit') }}" ><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>
                        @endcan

                        @can('access','delete_blog')
                        <form method="POST" action="{{ url('admin/blog-posts' . '/' . $blogpost->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
                        @endcan
                        <br/>
                        <br/>

                        <ul class="list-group">
                            <li class="list-group-item active">@lang('site.id')</li>
                            <li class="list-group-item">{{ $blogpost->id }}</li>
                            <li class="list-group-item active">@lang('site.title')</li>
                            <li class="list-group-item">{{ $blogpost->title }}</li>
                            <li class="list-group-item active">@lang('site.content')</li>
                            <li class="list-group-item">{!! clean( check($blogpost->content) ) !!}</li>
                            <li class="list-group-item active">@lang('site.enabled')</li>
                            <li class="list-group-item">{{ boolToString($blogpost->status) }}</li>
                            <li class="list-group-item active">@lang('site.cover-image')</li>
                            <li class="list-group-item">@if(!empty($blogpost->cover_photo))
                                    <img src="{{ asset($blogpost->cover_photo) }}" />
                                @endif</li>
                            <li class="list-group-item active">@lang('site.created-by')</li>
                            <li class="list-group-item">@if($blogpost->user()->exists())
                                    {{ $blogpost->user->name }}
                                @endif</li>
                            <li class="list-group-item active">@lang('site.meta-title')</li>
                            <li class="list-group-item">{{ $blogpost->meta_title }}</li>
                            <li class="list-group-item active">@lang('site.meta-description')</li>
                            <li class="list-group-item">{{ $blogpost->meta_description }}</li>
                            <li class="list-group-item active">@lang('site.categories')</li>
                            <li class="list-group-item"><ul>
                                    @foreach($blogpost->blogCategories as $category)
                                        <li>{{ $category->name }}</li>
                                    @endforeach
                                </ul></li>
                            <li class="list-group-item active">@lang('site.published-on')</li>
                            <li class="list-group-item">{{ $blogpost->publish_date }}</li>

                        </ul>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
