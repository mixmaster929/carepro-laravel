@extends('layouts.admin-page-wide')

@section('search-form',route('admin.attachments.index',['user'=>$user->id]))

@section('pageTitle',__('site.attachments'))
@section('page-title',__('site.attachments').': '.$user->name." ({$type})".(!empty(request('search')) ? ': '.request('search'):''))

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div >
                    <div  >
                        @can('access','view_candidate_attachments')
                        <a href="{{ $route }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        @endcan

                        @can('access','create_candidate_attachment')
                        <a href="{{ route('admin.attachments.create',['user'=>$user->id]) }}" class="btn btn-success btn-sm" title="@lang('site.add-new')">
                            <i class="fa fa-plus" aria-hidden="true"></i> @lang('site.add-new')
                        </a>
                        @endcan




                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th><th>@lang('site.name')</th>
                                        <th>@lang('site.type')</th>
                                        <th>@lang('site.added-on')</th>
                                        <th>@lang('site.author')</th>
                                        <th>@lang('site.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($attachments as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ( (Request::get('page',1)-1) * $perPage) }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            {{ pathinfo($item->path,PATHINFO_EXTENSION) }}
                                        </td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($item->created_at)->format('d/M/Y') }}</td>
                                        <td>{{ $item->author }}</td>
                                        <td>
                                            @can('access','view_candidate_attachment')
                                            @if(isImage($item->path))
                                             <a href="#"  data-toggle="modal" data-target="#pictureModal{{ $item->id }}"  title="@lang('site.view')"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> @lang('site.view')</button></a>
                                                <div class="modal fade" id="pictureModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="pictureModal{{ $item->id }}Label" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="pictureModal{{ $item->id }}Label">@lang('site.picture')</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body int_txcen" >
                                                                <img src="{{ route('admin.image') }}?file={{ $item->path }}" class="int_wm100pc"  />
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('site.close')</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>




                                            @endif
                                            <a href="{{ route('admin.download') }}?file={{ $item->path }}" title="@lang('site.download')"><button class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i> @lang('site.download')</button></a>
                                            @endcan

                                            @can('access','edit_candidate_attachment')
                                            <a href="{{ url('/admin/attachments/' . $item->id . '/edit') }}" title="@lang('site.edit')"><button class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> @lang('site.edit')</button></a>
                                            @endcan

                                            @can('access','delete_candidate_attachment')
                                            <form method="POST" action="{{ url('/admin/attachments' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
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
                            <div class="pagination-wrapper"> {!! clean( $attachments->appends(['search' => Request::get('search')])->render() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
