@extends('layouts.admin-page-wide')

@section('search-form',route('admin.employment-comments.index',['employment'=>$employment->id]))

@section('pageTitle',__('site.employment-comments'))
@section('page-title',$employment->employer->user->name.'('.__('site.employer').') - '.$employment->candidate->user->name.'('.__('site.candidate').')')

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div >
                    <div  >
                        @can('access','view_employments')
                        <a href="{{ route('admin.employments.index',['user'=>$employment->employer->user_id]) }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        @endcan

                        @can('access','create_employment_comment')
                        <a href="{{ route('admin.employment-comments.create',['employment'=>$employment->id]) }}" class="btn btn-success btn-sm" title="@lang('site.add-new')">
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
                                        <th>@lang('site.created-by')</th>
                                        <th>@lang('site.added-on')</th>
                                        <th>@lang('site.content')</th><th>@lang('site.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($employmentcomments as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ( (Request::get('page',1)-1) *$perPage) }}</td>
                                        <td>{{ $item->user->name }} ({{ roleName($item->user->role_id) }})</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($item->created_at)->format('d/M/Y') }}</td>
                                        <td>{{ limitLength(strip_tags($item->content),80) }}
                                        @if($item->employmentCommentAttachments()->count()>0)
                                            <i class="fa fa-paperclip"></i>
                                            @endif
                                        </td>
                                        <td>

                                            <div class="btn-group dropup">
                                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ni ni-settings"></i> @lang('site.actions')
                                                </button>
                                                <div class="dropdown-menu">
                                                    <!-- Dropdown menu links -->
                                                    @can('access','view_employment_comment')
                                                    <a class="dropdown-item" href="{{ url('/admin/employment-comments/' . $item->id) }}">@lang('site.view')</a>
                                                    @endcan

                                                    @can('access','edit_employment_comment')
                                                    <a class="dropdown-item" href="{{ url('/admin/employment-comments/' . $item->id . '/edit') }}">@lang('site.edit')</a>
                                                    @endcan

                                                    @can('access','delete_employment_comment')
                                                    <a class="dropdown-item" href="#" onclick="$('#deleteForm{{ $item->id }}').submit()">@lang('site.delete')</a>
                                                    @endcan



                                                </div>
                                            </div>
                                            <form  onsubmit="return confirm(&quot;@lang('site.confirm-delete')&quot;)"   id="deleteForm{{ $item->id }}"  method="POST" action="{{ url('/admin/employment-comments' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                            </form>


                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! clean( $employmentcomments->appends(request()->input())->render() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
