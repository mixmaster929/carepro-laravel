@extends('layouts.admin-page')

@section('search-form',route('admin.notes.index',['user'=>$user->id]))

@section('pageTitle',__('site.notes'))
@section('page-title',__('site.notes').': '.$user->name." ({$type})".(!empty(request('search')) ? ': '.request('search'):''))

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div >
                    <div  >

                        <a href="{{ $route }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>


                        @can('access','create_candidate_note')
                        <a href="{{ route('admin.notes.create',['user'=>$user->id]) }}" class="btn btn-success btn-sm" title="@lang('site.add-new')">
                            <i class="fa fa-plus" aria-hidden="true"></i> @lang('site.add-new')
                        </a>
                        @endcan




                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th><th>@lang('site.title')</th><th>@lang('site.added-on')</th>
                                        <th>@lang('site.author')</th>
                                        <th>@lang('site.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($notes as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ( (Request::get('page',1)-1) * $perPage) }}</td>
                                        <td>{{ $item->title }}</td><td>{{ \Illuminate\Support\Carbon::parse($item->created_at)->format('d/M/Y') }}</td>
                                        <td>{{ $item->author }}</td>
                                        <td>
                                            @can('access','view_candidate_note')
                                            <a href="{{ url('/admin/notes/' . $item->id) }}" title="@lang('site.view')"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> @lang('site.view')</button></a>
                                            @endcan

                                            @can('access','edit_candidate_note')
                                            <a href="{{ url('/admin/notes/' . $item->id . '/edit') }}" title="@lang('site.edit')"><button class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> @lang('site.edit')</button></a>
                                            @endcan

                                            @can('access','delete_candidate_note')
                                            <form method="POST" action="{{ url('/admin/notes' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
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
                            <div class="pagination-wrapper"> {!! clean( $notes->appends(['search' => Request::get('search')])->render() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
