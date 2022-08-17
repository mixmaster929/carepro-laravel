@extends('layouts.admin-page-wide')

@section('search-form',url('/admin/interviews'))

@section('pageTitle',__('site.interviews'))
@section('page-title')
    {{ $title }} ({{ $interviews->count() }})
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
                        @can('access','create_interview')
                        <a href="{{ url('/admin/interviews/create') }}" class="btn btn-success btn-sm" title="@lang('site.add-new')">
                            <i class="fa fa-plus" aria-hidden="true"></i> @lang('site.add-new')
                        </a>
                        @endcan

                        <a data-toggle="collapse" href="#filterCollapse" role="button" aria-expanded="false" aria-controls="filterCollapse" class="btn btn-primary btn-sm" title="@lang('site.filter')">
                            <i class="fa fa-filter" aria-hidden="true"></i> @lang('site.filter')
                        </a>

                        <a  href="{{ route('admin.interviews.index') }}" class="btn btn-info btn-sm" title="@lang('site.reset')">
                            <i class="fa fa-sync" aria-hidden="true"></i> @lang('site.reset')
                        </a>

                        <div  class="collapse int_tm20"  id="filterCollapse" >
                            <div  >
                                <form action="{{ route('admin.interviews.index') }}" method="get">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="search" class="control-label">@lang('site.search')</label>
                                                <input class="form-control" type="text" value="{{ request()->search  }}" name="search"/>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group  {{ $errors->has('type') ? 'has-error' : ''}}">
                                                <label for="type" class="control-label">@lang('site.type')</label>
                                                <select name="type" class="form-control" id="type" >
                                                    <option></option>
                                                    @foreach (json_decode('{"u":"'.__('site.upcoming').'","p":"'.__('site.past').'"}', true) as $optionKey => $optionValue)
                                                        <option value="{{ $optionKey }}" {{ ((null !== old('type',@request()->type)) && old('type',@request()->type) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                                                    @endforeach
                                                </select>
                                                {!! clean( $errors->first('type', '<p class="help-block">:message</p>') ) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label for="min_date" class="control-label">@lang('site.min-date')</label>
                                                <input class="form-control date" type="text" value="{{ request()->min_date  }}" name="min_date"/>
                                            </div>

                                        </div>
                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label for="max_date" class="control-label">@lang('site.max-date')</label>
                                                <input class="form-control date" type="text" value="{{ request()->max_date  }}" name="max_date"/>
                                            </div>

                                        </div>




                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary btn-block">@lang('site.filter')</button>
                                    </div>

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
                                        <th>@lang('site.employer')</th>
                                        <th>@lang('site.interview-date')</th>
                                        <th>@lang('site.time')</th>
                                        <th>@lang('site.status')</th>
                                        <th>@lang('site.candidates')</th>
                                        <th>@lang('site.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($interviews as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ( (Request::get('page',1)-1) *$perPage) }}</td>
                                        <td><a target="_blank" href="{{ userLink($item->user) }}">{{ $item->user->name }}</a></td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($item->interview_date)->format('d/M/Y') }}</td>
                                        <td>{{ $item->interview_time }}</td>
                                        <td>
                                            @if(\Illuminate\Support\Carbon::parse($item->interview_date)->timestamp > time())
                                                @lang('site.upcoming')
                                                @else
                                            @lang('site.past')
                                            @endif

                                        </td>
                                        <td>{{ $item->candidates()->count() }}</td>
                                        <td>

                                            <div class="btn-group dropup">
                                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ni ni-settings"></i> @lang('site.actions')
                                                </button>
                                                <div class="dropdown-menu">
                                                    <!-- Dropdown menu links -->
                                                     @can('access','view_interview')
                                                    <a class="dropdown-item" href="{{ url('/admin/interviews/' . $item->id) }}">@lang('site.view')</a>
                                                    @endcan

                                                    @can('access','edit_interview')
                                                     <a class="dropdown-item" href="{{ url('/admin/interviews/' . $item->id . '/edit') }}">@lang('site.edit')</a>
                                                    @endcan

                                                    @can('access','delete_interview')
                                                    <a class="dropdown-item" href="#" onclick="$('#deleteForm{{ $item->id }}').submit()">@lang('site.delete')</a>
                                                    @endcan



                                                </div>
                                            </div>

                                            <form  onsubmit="return confirm(&quot;@lang('site.confirm-delete')&quot;)"   id="deleteForm{{ $item->id }}"  method="POST" action="{{ url('/admin/interviews' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! clean( $interviews->appends(request()->input())->render() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    <script src="{{ asset('vendor/pickadate/picker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/picker.date.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/picker.time.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/legacy.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/order-search.js') }}"></script>

@endsection


@section('header')
    @parent
    <link href="{{ asset('vendor/pickadate/themes/default.date.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/pickadate/themes/default.time.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/pickadate/themes/default.css') }}" rel="stylesheet">


@endsection
