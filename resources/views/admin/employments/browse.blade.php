@extends('layouts.admin-page-wide')

@section('search-form',route('admin.employments.browse'))

@section('pageTitle',__('site.employments'))

@section('page-title')
    @lang('site.employments') ({{ $employments->count() }})
    @if(Request::get('search'))
        : {{ Request::get('search') }}
    @endif
@endsection
@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div >

                    <div>
                        @if(!empty($filterParams))
                            <ul  class="list-inline">
                                <li class="list-inline-item" ><strong>@lang('site.filter'):</strong></li>
                                @foreach($filterParams as $param)
                                    @if(null !== request()->get($param)  && request()->get($param) != '')
                                        <li class="list-inline-item" >{{ ucwords(str_ireplace('_',' ',$param)) }}</li>
                                    @endif
                                @endforeach

                            </ul>
                        @endif
                    </div>

                    <div  >


                        @can('access','create_employment')
                        <a href="{{ route('admin.employments.create-employment') }}" class="btn btn-success btn-sm" title="@lang('site.add-new')">
                            <i class="fa fa-plus" aria-hidden="true"></i> @lang('site.add-new')
                        </a>
                        @endcan

                        <a data-toggle="modal" data-target="#filterModal" href="#" class="btn btn-primary btn-sm" title="@lang('site.filter')">
                            <i class="fa fa-filter" aria-hidden="true"></i> @lang('site.filter')
                        </a>

                        <a  href="{{ route('admin.employments.browse') }}" class="btn btn-info btn-sm" title="@lang('site.filter')">
                            <i class="fa fa-sync" aria-hidden="true"></i> @lang('site.reset')
                        </a>

                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('site.employer')</th>
                                    <th>@lang('site.candidate')</th>
                                    <th>@lang('site.start-date')</th>
                                    <th>@lang('site.end-date')</th>
                                    <th>@lang('site.active')</th>
                                    <th>@lang('site.salary')</th>
                                    <th>@lang('site.actions')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($employments as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ( (Request::get('page',1)-1) * $perPage) }}</td>

                                        <td><a href="{{ route('admin.employers.show',['employer'=>$item->employer->user_id]) }}">{{ $item->employer->user->name }}</a></td>
                                        <td><a href="{{ route('admin.candidates.show',['candidate'=>$item->candidate->user_id]) }}">{{ $item->candidate->user->name }}</a></td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($item->start_date)->format('d/M/Y') }}</td>
                                        <td>@if(!empty($item->end_date))
                                                {{ \Illuminate\Support\Carbon::parse($item->end_date)->format('d/M/Y') }}
                                            @endif</td>
                                        <td>{{ boolToString($item->active) }}</td>
                                        <td>
                                            @if(!empty($item->salary))
                                            {{ price($item->salary) }} {{ salaryType($item->salary_type) }}

                                                @endif

                                        </td>
                                        <td>
                                            @can('access','view_employment_comments')
                                            <a href="{{ route('admin.employment-comments.index',['employment'=>$item->id]) }}" title="@lang('site.view')"><button class="btn btn-info btn-sm"><i class="fa fa-comments" aria-hidden="true"></i> @lang('site.comments') ({{ $item->employmentComments()->count() }})</button></a>
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








    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.employments.browse') }}" method="get">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filterModalLabel">@lang('site.filter')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="@lang('site.close')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="search" class="control-label">@lang('site.search')</label>
                                <input class="form-control" type="text" value="{{ request()->search  }}" name="search"/>
                            </div>

                            <div class="form-group col-md-6 {{ $errors->has('active') ? 'has-error' : ''}}">
                                <label for="active" class="control-label">@lang('site.active')</label>
                                <select name="active" class="form-control" id="active" >
                                    <option></option>
                                    @foreach (json_decode('{"0":"'.__('site.no').'","1":"'.__('site.yes').'"}', true) as $optionKey => $optionValue)
                                        <option value="{{ $optionKey }}" {{ ((null !== old('active',@request()->active)) && old('employed',@request()->active) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                                    @endforeach
                                </select>
                                {!! clean( $errors->first('active', '<p class="help-block">:message</p>') ) !!}
                            </div>
                        </div>

                        <div class="row">

                            <div class="form-group col-md-6">
                                <label for="min_salary" class="control-label">@lang('site.min-salary')</label>
                                <input class="form-control digit" type="text" value="{{ request()->min_salary  }}" name="min_salary"/>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="max_salary" class="control-label">@lang('site.max-salary')</label>
                                <input class="form-control digit" type="text" value="{{ request()->max_salary  }}" name="max_salary"/>
                            </div>

                        </div>




                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('site.close')</button>
                        <button type="submit" class="btn btn-primary">@lang('site.filter')</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
