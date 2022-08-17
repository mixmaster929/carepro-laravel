@extends('layouts.admin-page-wide')

@section('search-form',url('/admin/employers'))

@section('pageTitle',__('site.employers'))

    @section('page-title')
        @lang('site.employers') ({{ $total }})
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

                    <div>
                    <div class="row">

                        <div class="col-md-8">

                            @can('access','create_employer')
                            <a href="{{ url('/admin/employers/create') }}" class="btn btn-success btn-sm" title="@lang('site.add-new')">
                                <i class="fa fa-plus" aria-hidden="true"></i> @lang('site.add-new')
                            </a>
                            @endcan

                            <a data-toggle="modal" data-target="#filterModal" href="#" class="btn btn-primary btn-sm" title="@lang('site.filter')">
                                <i class="fa fa-filter" aria-hidden="true"></i> @lang('site.filter')
                            </a>

                            <a  href="{{ route('admin.employers.index') }}" class="btn btn-info btn-sm" title="@lang('site.filter')">
                                <i class="fa fa-sync" aria-hidden="true"></i> @lang('site.reset')
                            </a>

                            <a  href="{{ route('admin.employers.export') }}?{{ http_build_query(request()->all()) }}" class="btn btn-secondary btn-sm" title="@lang('site.filter')">
                                <i class="fa fa-download" aria-hidden="true"></i> @lang('site.export')
                            </a>


                        </div>

                        <div class="col-md-4">


                            <form id="sort-form" method="get" action="{{ route('admin.employers.index') }}">
                                @foreach(request()->all() as $key=>$value)
                                    @if($key != 'sort')
                                        <input type="hidden" value="{{ $value }}" name="{{ $key }}"/>
                                    @endif
                                @endforeach

                                <select onchange="$('#sort-form').submit()" class="form-control" name="sort" >
                                    <option value="" disabled    >@lang('site.sort-by')</option>
                                    @foreach(['a'=>__('site.name-ascending'),'d'=>__('site.name-descending'),'n'=>__('site.newest'),'o'=>__('site.oldest')] as $key=>$value)
                                        <option @if($sort==$key) selected @endif value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>

                            </form>

                        </div>

                    </div>



                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('site.name')</th>
                                        <th>@lang('site.employed')</th>
                                        <th>@lang('site.telephone')</th>
                                        <th>@lang('site.email')</th>
                                        <th>@lang('site.active')</th>
                                        <th>@lang('site.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($employers as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ( (Request::get('page',1)-1) * $perPage) }}</td>

                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->employer->employments->count() }}</td>
                                        <td>{{  $item->telephone }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ boolToString($item->employer->active) }}</td>
                                        <td>
                                            <div class="btn-group dropleft">
                                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ni ni-settings"></i> @lang('site.actions')
                                                </button>
                                                <div class="dropdown-menu">
                                                    <!-- Dropdown menu links -->
                                                    @can('access','view_employer')
                                                    <a class="dropdown-item" href="{{ url('/admin/employers/' . $item->id) }}">@lang('site.view')</a>
                                                    @endcan

                                                    @can('access','edit_employer')
                                                    <a class="dropdown-item" href="{{ url('/admin/employers/' . $item->id . '/edit') }}">@lang('site.edit')</a>
                                                    @endcan

                                                    @can('access','delete_employer')
                                                    <a class="dropdown-item" href="#" onclick="$('#deleteForm{{ $item->id }}').submit()">@lang('site.delete')</a>
                                                    @endcan



                                                </div>
                                            </div>

                                            <div class="btn-group dropleft">
                                                <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-book"></i> @lang('site.records')
                                                </button>
                                                <div class="dropdown-menu">
                                                    @can('access','view_employments')
                                                    <a class="dropdown-item" href="{{ route('admin.employments.index',['user'=>$item->id]) }}">@lang('site.employment-records') ({{ $item->employer->employments()->count() }})</a>
                                                    @endcan
                                                    @can('access','view_invoices')
                                                    <a class="dropdown-item" href="{{ route('admin.invoices.index') }}?user={{ $item->id }}">@lang('site.invoices') ({{ $item->invoices()->count() }})</a>
                                                    @endcan
                                                    @can('access','view_employer_notes')
                                                    <a class="dropdown-item" href="{{ route('admin.notes.index',['user'=>$item->id]) }}">@lang('site.notes') ({{ $item->userNotes()->count() }})</a>
                                                    @endcan

                                                    @can('access','view_employer_attachments')
                                                    <a class="dropdown-item" href="{{ route('admin.attachments.index',['user'=>$item->id]) }}">@lang('site.attachments') ({{ $item->userAttachments()->count() }})</a>
                                                    @endcan

                                                        @can('access','view_contracts')
                                                            <a class="dropdown-item" href="{{ route('admin.contracts.index') }}?user_id={{ $item->id }}">@lang('site.contracts') ({{ $item->contracts()->count() }})</a>
                                                        @endcan


                                                </div>
                                            </div>
                                            <form onsubmit="return confirm(&quot;@lang('site.confirm-delete')&quot;)" id="deleteForm{{ $item->id }}" method="POST" action="{{ url('/admin/employers' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                             </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! clean( $employers->appends(request()->input())->render() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>








    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.employers.index') }}" method="get">
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

                    <div class="form-group col-md-6 {{ $errors->has('gender') ? 'has-error' : ''}}">
                        <label for="gender" class="control-label">@lang('site.gender')</label>
                        <select name="gender" class="form-control" id="gender" >
                            <option></option>
                            @foreach (json_decode('{"f":"'.__('site.female').'","m":"'.__('site.male').'"}', true) as $optionKey => $optionValue)
                                <option value="{{ $optionKey }}" {{ ((null !== old('gender',@request()->gender)) && old('gender',@request()->gender) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                            @endforeach
                        </select>
                        {!! clean( $errors->first('gender', '<p class="help-block">:message</p>') ) !!}
                    </div>
                </div>

                    <div class="form-group">
                        <label for="field_id" class="control-label">@lang('site.custom-field')</label>
                        <div class="row">
                            <div class="col-md-5">
                                <select class="form-control" name="field_id" id="field_id">
                                    <option ></option>
                                    @foreach(\App\EmployerField::orderBy('sort_order')->where('type','!=','file')->get() as $field)
                                        <option @if($field->id==request()->get('field_id')) selected @endif value="{{ $field->id }}">{{ $field->name }}</option>
                                        @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                =
                            </div>
                            <div class="col-md-6">
                                <input class="form-control" type="text" name="custom_field" value="{{ request()->get('custom_field') }}"/>
                            </div>
                        </div>

                    </div>


                    <div class="row">
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
