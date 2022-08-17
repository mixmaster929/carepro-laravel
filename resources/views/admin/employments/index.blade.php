@extends('layouts.admin-page-wide')



@section('pageTitle',__('site.employers'))
@section('page-title',__('site.employment-records').' : '.$user->name)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div >
                    <div  >
                        @can('access','view_employers')
                        <a href="{{ route('admin.employers.index') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        @endcan

                        @can('access','create_employment')
                        <a href="{{ route('admin.employments.create',['user'=>$user->id]) }}" class="btn btn-success btn-sm" title="@lang('site.add-new')">
                            <i class="fa fa-plus" aria-hidden="true"></i> @lang('site.add-new')
                        </a>
                        @endcan



                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('site.candidate')</th>
                                        <th>@lang('site.start-date')</th><th>@lang('site.end-date')</th>
                                        <th>@lang('site.comments')</th>
                                        <th>@lang('site.active')</th><th>@lang('site.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($employments as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ( (Request::get('page',1)-1) * $perPage) }}</td>
                                        <td>{{ $item->candidate->user->name }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($item->start_date)->format('d/M/Y') }}</td><td>
                                            @if(!empty($item->end_date))
                                            {{ \Illuminate\Support\Carbon::parse($item->end_date)->format('d/M/Y') }}
                                            @endif
                                        </td>
                                        <td>{{ $item->employmentComments()->count() }}</td>
                                        <td>{{ boolToString($item->active) }}</td>
                                        <td>
                                            @can('access','view_employment_comments')
                                            <a href="{{ route('admin.employment-comments.index',['employment'=>$item->id]) }}" title="@lang('site.view')"><button class="btn btn-info btn-sm"><i class="fa fa-comments" aria-hidden="true"></i> @lang('site.comments')</button></a>
                                             @endcan

                                            <div class="btn-group dropup">
                                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ni ni-settings"></i> @lang('site.actions')
                                                </button>
                                                <div class="dropdown-menu">
                                                    <!-- Dropdown menu links -->
                                                    @can('access','view_employment')
                                                    <a class="dropdown-item" href="{{ url('/admin/employments/' . $item->id) }}">@lang('site.view')</a>
                                                    @endcan

                                                    @can('access','edit_employment')
                                                    <a class="dropdown-item" href="{{ url('/admin/employments/' . $item->id . '/edit') }}">@lang('site.edit')</a>
                                                    @endcan

                                                    @can('access','delete_employment')
                                                    <a class="dropdown-item" href="#" onclick="$('#deleteForm{{ $item->id }}').submit()">@lang('site.delete')</a>
                                                    @endcan



                                                </div>
                                            </div>
                                            <form  onsubmit="return confirm(&quot;@lang('site.confirm-delete')&quot;)"  id="deleteForm{{ $item->id }}" method="POST" action="{{ url('/admin/employments' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                             </form>







                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! clean( $employments->appends(request()->input())->render() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
