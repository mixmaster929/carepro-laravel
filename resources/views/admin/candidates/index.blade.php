@extends('layouts.admin-page-wide')

@section('search-form',url('/admin/candidates'))

@section('pageTitle',__('site.candidates'))

    @section('page-title')
        @lang('site.candidates') ({{ $total }})
        @if(Request::get('category') && \App\Category::find(request()->category))
            : {{ \App\Category::find(request()->category)->name }}
        @endif
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

                        <div class="row">
                            <div class="col-md-8">
                                @can('access','create_candidate')
                                <a href="{{ url('/admin/candidates/create') }}" class="btn btn-success btn-sm" title="@lang('site.add-new')">
                                    <i class="fa fa-plus" aria-hidden="true"></i> @lang('site.add-new')
                                </a>
                                @endcan

                                <a data-toggle="modal" data-target="#filterModal" href="#" class="btn btn-primary btn-sm" title="@lang('site.filter')">
                                    <i class="fa fa-filter" aria-hidden="true"></i> @lang('site.filter')
                                </a>

                                <a  href="{{ route('admin.candidates.index') }}" class="btn btn-info btn-sm" title="@lang('site.filter')">
                                    <i class="fa fa-sync" aria-hidden="true"></i> @lang('site.reset')
                                </a>

                                <a  href="{{ route('admin.candidates.export') }}?{{ http_build_query(request()->all()) }}" class="btn btn-secondary btn-sm" title="@lang('site.filter')">
                                    <i class="fa fa-download" aria-hidden="true"></i> @lang('site.export')
                                </a>

                            </div>
                            <div class="col-md-4">
                                <form id="sort-form" method="get" action="{{ route('admin.candidates.index') }}">
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
                                        <th>@lang('site.picture')</th>
                                        <th>@lang('site.name')</th>
                                        <th>@lang('site.gender')</th>
                                        <th>@lang('site.age')</th>
                                        <th>@lang('site.email')</th>
                                        <th>@lang('site.visibility')</th>
                                        <th>@lang('site.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($candidates as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ( (Request::get('page',1)-1) * $perPage) }}</td>
                                        <td>
                                            <div class="media align-items-center">
              <span class="avatar avatar-sm rounded-circle">
                  @if(!empty($item->candidate->picture) && file_exists($item->candidate->picture))
                      <img   src="{{ asset($item->candidate->picture) }}">
                      @else
                      <img   src="{{ asset('img/man.jpg') }}">
                  @endif

              </span>
                                            </div>


                                        </td>
                                        <td>{{ $item->name }} @if($item->name != $item->candidate->display_name)({{ $item->candidate->display_name }})@endif</td>
                                        <td>{{ gender($item->candidate->gender) }}</td>
                                        <td>{{  getAge(\Illuminate\Support\Carbon::parse($item->candidate->date_of_birth)->timestamp) }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ empty($item->candidate->public)? __('site.private'):__('site.public') }}</td>
                                        <td>
                                            <div class="btn-group dropup">
                                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ni ni-settings"></i> @lang('site.actions')
                                                </button>
                                                <div class="dropdown-menu">
                                                    <!-- Dropdown menu links -->
                                                    @can('access','view_candidate')
                                                    <a class="dropdown-item" href="{{ url('/admin/candidates/' . $item->id) }}">@lang('site.view')</a>
                                                    @endcan

                                                    @can('access','edit_candidate')
                                                    <a class="dropdown-item" href="{{ url('/admin/candidates/' . $item->id . '/edit') }}">@lang('site.edit')</a>
                                                    @endcan

                                                    @can('access','delete_candidate')
                                                    <a class="dropdown-item" href="#" onclick="$('#deleteForm{{ $item->id }}').submit()">@lang('site.delete')</a>
                                                    @endcan

                                                    @can('access','view_candidate')
                                                    <div class="dropdown-divider"></div>
                                                    <h6 class="dropdown-header">@lang('site.download-profile')</h6>
                                                    <a class="dropdown-item" href="{{ route('admin.candidate.download',['id'=>$item->id,'full'=>1]) }}">@lang('site.full-profile')</a>
                                                    <a class="dropdown-item" href="{{ route('admin.candidate.download',['id'=>$item->id,'full'=>0]) }}">@lang('site.partial-profile')</a>
                                                    <div class="dropdown-divider"></div>
                                                    @endcan

                                                    @can('access','view_test_results')
                                                    <a class="dropdown-item" href="{{ route('admin.candidates.tests',['user'=>$item->id]) }}" >@lang('site.test-results') ({{ $item->userTests()->count() }})</a>
                                                    @endcan


                                                </div>
                                            </div>

                                            <div class="btn-group dropup">
                                                <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-book"></i> @lang('site.records')
                                                </button>
                                                <div class="dropdown-menu">
                                                    @can('access','view_candidate_notes')
                                                    <a class="dropdown-item" href="{{ route('admin.notes.index',['user'=>$item->id]) }}">@lang('site.notes') ({{ $item->userNotes()->count() }})</a>
                                                  @endcan

                                                    @can('access','view_candidate_attachments')
                                                    <a class="dropdown-item" href="{{ route('admin.attachments.index',['user'=>$item->id]) }}">@lang('site.attachments') ({{ $item->userAttachments()->count() }})</a>
                                                    @endcan

                                                    @can('access','view_invoices')
                                                    <a class="dropdown-item" href="{{ route('admin.invoices.index') }}?user={{ $item->id }}">@lang('site.invoices') ({{ $item->invoices()->count() }})</a>
                                                    @endcan

                                                    @can('access','view_contracts')
                                                            <a class="dropdown-item" href="{{ route('admin.contracts.index') }}?user_id={{ $item->id }}">@lang('site.contracts') ({{ $item->contracts()->count() }})</a>
                                                    @endcan
                                                </div>
                                            </div>
                                            <form onsubmit="return confirm(&quot;@lang('site.confirm-delete')&quot;)" id="deleteForm{{ $item->id }}" method="POST" action="{{ url('/admin/candidates' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                             </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! clean( $candidates->appends(request()->input())->render() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>








    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.candidates.index') }}" method="get">
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
                    <div class="row">
                    <div class="form-group col-md-6">
                        <label for="search" class="control-label">@lang('site.min-age')</label>
                        <select class="form-control" name="min_age" id="min_age">
                            <option></option>
                            @foreach(range(1,100) as $value)
                                <option @if(request()->min_age==$value) selected  @endif value="{{ $value }}">{{ $value }}</option>
                             @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="search" class="control-label">@lang('site.max-age')</label>
                        <select class="form-control" name="max_age" id="max_age">
                            <option></option>
                            @foreach(range(1,100) as $value)
                                <option @if(request()->max_age==$value) selected  @endif value="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                    <div class="form-group">
                        <label for="field_id" class="control-label">@lang('site.custom-field')</label>
                        <div class="row">
                            <div class="col-md-5">
                                <select class="form-control" name="field_id" id="field_id">
                                    <option ></option>
                                    @foreach(\App\CandidateField::orderBy('sort_order')->where('type','!=','file')->get() as $field)
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
                    <div class="form-group col-md-6 {{ $errors->has('employed') ? 'has-error' : ''}}">
                        <label for="employed" class="control-label">@lang('site.employed')</label>
                        <select name="employed" class="form-control" id="employed" >
                            <option></option>
                            @foreach (json_decode('{"0":"'.__('site.no').'","1":"'.__('site.yes').'"}', true) as $optionKey => $optionValue)
                                <option value="{{ $optionKey }}" {{ ((null !== old('employed',@request()->employed)) && old('employed',@request()->employed) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                            @endforeach
                        </select>
                        {!! clean( $errors->first('employed', '<p class="help-block">:message</p>') ) !!}
                    </div>
                    <div class="form-group  col-md-6{{ $errors->has('shortlisted') ? 'has-error' : ''}}">
                        <label for="shortlisted" class="control-label">@lang('site.shortlisted')</label>
                        <select name="shortlisted" class="form-control" id="shortlisted" >
                            <option></option>
                            @foreach (json_decode('{"0":"'.__('site.no').'","1":"'.__('site.yes').'"}', true) as $optionKey => $optionValue)
                                <option value="{{ $optionKey }}" {{ ((null !== old('shortlisted',@request()->shortlisted)) && old('shortlisted',@request()->shortlisted) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                            @endforeach
                        </select>
                        {!! clean( $errors->first('shortlisted', '<p class="help-block">:message</p>') ) !!}
                    </div>
                </div>

                    <div class="row">
                    <div class="form-group col-md-6 {{ $errors->has('locked') ? 'has-error' : ''}}">
                        <label for="locked" class="control-label">@lang('site.locked')</label>
                        <select name="locked" class="form-control" id="locked" >
                            <option></option>
                            @foreach (json_decode('{"0":"'.__('site.no').'","1":"'.__('site.yes').'"}', true) as $optionKey => $optionValue)
                                <option value="{{ $optionKey }}" {{ ((null !== old('locked',@request()->locked)) && old('locked',@request()->locked) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                            @endforeach
                        </select>
                        {!! clean( $errors->first('locked', '<p class="help-block">:message</p>') ) !!}
                    </div>
                    <div class="form-group col-md-6 {{ $errors->has('status') ? 'has-error' : ''}}">
                        <label for="status" class="control-label">@lang('site.enabled')</label>
                        <select name="status" class="form-control" id="status" >
                            <option></option>
                            @foreach (json_decode('{"1":"'.__('site.yes').'","0":"'.__('site.no').'"}', true) as $optionKey => $optionValue)
                                <option value="{{ $optionKey }}" {{ ((null !== old('status',@request()->status)) && old('status',@request()->status) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                            @endforeach
                        </select>
                        {!! clean( $errors->first('status', '<p class="help-block">:message</p>') ) !!}
                    </div>
                </div>

                    <div class="row">
                    <div class="form-group col-md-6 {{ $errors->has('public') ? 'has-error' : ''}}">
                        <label for="public" class="control-label">@lang('site.public')</label>
                        <select name="public" class="form-control" id="public" >
                            <option></option>
                            @foreach (json_decode('{"0":"'.__('site.no').'","1":"'.__('site.yes').'"}', true) as $optionKey => $optionValue)
                                <option value="{{ $optionKey }}" {{ ((null !== old('public',@request()->public)) && old('public',@request()->public) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                            @endforeach
                        </select>
                        {!! clean( $errors->first('public', '<p class="help-block">:message</p>') ) !!}
                    </div>


                <div class="form-group col-md-6">
                    <label for="category">@lang('site.category')</label>
                        <select    name="category" id="category" class="form-control">
                            <option></option>
                            @foreach(\App\Category::orderBy('name')->get() as $category)
                                <option   {{ ((null !== old('category',@request()->category)) && old('category',@request()->category) == @$category->id) ? 'selected' : ''}}  value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
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
