@extends('layouts.admin-page')

@section('search-form',url('/admin/articles'))
@section('pageTitle',__('site.articles'))
@section('page-title')
    {{ __('site.manage-articles') }} ({{ $articles->count() }})
    @if(Request::get('search'))
        : {{ Request::get('search') }}
    @endif
@endsection

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div >
                    <div  >
                        @can('access','create_article')
                        <a href="{{ url('/admin/articles/create') }}" class="btn btn-success btn-sm" title="@lang('site.add-new')">
                            <i class="fa fa-plus" aria-hidden="true"></i> @lang('site.add-new')
                        </a>
                        @endcan



                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th><th>@lang('site.title')</th><th>@lang('site.slug')</th>
                                        <th>@lang('site.status')</th>
                                        <th>@lang('site.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($articles as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ( (Request::get('page',1)-1) *$perPage) }}</td>
                                        <td>{{ $item->title }}</td><td>{{ $item->slug }}</td>
                                        <td>{{ $item->status==1? __('site.enabled'):__('site.disabled') }}</td>
                                        <td>
                                            @can('access','view_article')
                                            <a href="{{ url('/admin/articles/' . $item->id) }}" title="@lang('site.view')"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> @lang('site.view')</button></a>
                                            @endcan

                                            @can('access','edit_article')
                                            <a href="{{ url('/admin/articles/' . $item->id . '/edit') }}" title="@lang('site.edit')"><button class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> @lang('site.edit')</button></a>
                                            @endcan

                                            @can('access','delete_article')
                                            <form method="POST" action="{{ url('/admin/articles' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                                <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')&quot;)"><i class="fa fa-trash" aria-hidden="true"></i> @lang('site.delete')</button>
                                            </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! clean( $articles->appends(['search' => Request::get('search')])->render() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
