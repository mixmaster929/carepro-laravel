@extends('layouts.admin-page')

@section('search-form',url('/admin/blog-posts'))

@section('pageTitle',__('site.blog-posts'))
@section('page-title',__('site.blog-posts'))

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div>
                    <div>
                        @can('access','create_blog')
                        <a href="{{ url('/admin/blog-posts/create') }}" class="btn btn-success btn-sm" title="@lang('site.add-new')">
                            <i class="fa fa-plus" aria-hidden="true"></i> @lang('site.add-new')
                        </a>
                        @endcan



                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th><th>@lang('site.title')</th><th>@lang('site.published-on')</th>
                                        <th>@lang('site.enabled')</th>
                                        <th>@lang('site.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($blogposts as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->title }}</td> <td>{{ \Illuminate\Support\Carbon::parse($item->publish_date)->format('d/M/Y') }}</td>
                                        <td>{{ boolToString($item->status) }}</td>
                                        <td>

                                            @can('access','view_blog')
                                            <a href="{{ url('/admin/blog-posts/' . $item->id) }}" title="@lang('site.view')"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> @lang('site.view')</button></a>
                                           @endcan

                                            @can('access','edit_blog')
                                            <a href="{{ url('/admin/blog-posts/' . $item->id . '/edit') }}" title="@lang('site.edit')"><button class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> @lang('site.edit')</button></a>
                                            @endcan

                                            @can('access','delete_blog')
                                            <form method="POST" action="{{ url('/admin/blog-posts' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash" aria-hidden="true"></i> @lang('site.delete')</button>
                                            </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! clean( $blogposts->appends(['search' => Request::get('search')])->render() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
