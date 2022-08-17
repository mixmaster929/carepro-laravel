@extends('layouts.admin-page')

@section('search-form',url('/admin/email-resources'))

@section('pageTitle',__('site.email-resources'))
@section('page-title',__('site.email-resources'))

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div >
                    <div  >
                        @can('access','create_email_resource')
                        <a href="{{ url('/admin/email-resources/create') }}" class="btn btn-success btn-sm" title="@lang('site.add-new')">
                            <i class="fa fa-plus" aria-hidden="true"></i> @lang('site.add-new')
                        </a>
                        @endcan


                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th><th>@lang('site.name')</th><th>@lang('site.file')</th><th>@lang('site.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($emailresources as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ( (Request::get('page',1)-1) *$perPage) }}</td>
                                        <td>{{ $item->name }}</td><td>{{ $item->file_name }}</td>
                                        <td>

                                            @can('access','view_email_resource')
                                            @if(isImage($item->file_path))
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
                                                            <div class="modal-body int_txcen"  >
                                                                <img src="{{ route('admin.image') }}?file={{ $item->file_path }}" class="int_txcen" />
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('site.close')</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>




                                            @endif
                                            <a href="{{ url('/admin/email-resources/' . $item->id ) }}" title="@lang('site.download')"><button class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i> @lang('site.download')</button></a>
                                            @endcan

                                            <div class="btn-group dropup">
                                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ni ni-settings"></i> @lang('site.actions')
                                                </button>
                                                <div class="dropdown-menu">



                                                    @can('access','edit_email_resource')
                                                    <a class="dropdown-item" href="{{ url('/admin/email-resources/' . $item->id . '/edit') }}">@lang('site.edit')</a>
                                                    @endcan


                                                    @can('access','delete_email_resource')
                                                    <a class="dropdown-item" href="#" onclick="$('#deleteForm{{ $item->id }}').submit()">@lang('site.delete')</a>
                                                    @endcan




                                                </div>
                                            </div>

                                            <form  onsubmit="return confirm(&quot;@lang('site.confirm-delete')&quot;)"   id="deleteForm{{ $item->id }}"  method="POST" action="{{ url('/admin/email-resources' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! clean( $emailresources->appends(request()->input())->render() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
