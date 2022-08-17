@extends('layouts.admin-page-wide')

@section('search-form',url('/admin/vacancies'))

@section('pageTitle',__('site.vacancies'))

@section('page-title')
    {{ __('site.vacancies') }} ({{ $vacancies->count() }})

    @if(Request::get('category') && \App\JobCategory::find(request()->category))
        : {{ \App\JobCategory::find(request()->category)->name }}
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
                        @can('access','create_vacancy')
                        <a href="{{ url('/admin/vacancies/create') }}" class="btn btn-success btn-sm" title="@lang('site.add-new')">
                            <i class="fa fa-plus" aria-hidden="true"></i> @lang('site.add-new')
                        </a>
                        @endcan

                        <a data-toggle="collapse" href="#filterCollapse" role="button" aria-expanded="false" aria-controls="filterCollapse" class="btn btn-primary btn-sm" title="@lang('site.filter')">
                            <i class="fa fa-filter" aria-hidden="true"></i> @lang('site.filter')
                        </a>

                        <a  href="{{ route('admin.vacancies.index') }}" class="btn btn-info btn-sm" title="@lang('site.reset')">
                            <i class="fa fa-sync" aria-hidden="true"></i> @lang('site.reset')
                        </a>

                        <div  class="collapse int_tm20"  id="filterCollapse" >
                            <div  >
                                <form action="{{ route('admin.vacancies.index') }}" method="get">

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="search" class="control-label">@lang('site.search')</label>
                                                <input class="form-control" type="text" value="{{ request()->search  }}" name="search"/>
                                            </div>
                                        </div>


                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="category" class="control-label">@lang('site.category')</label>
                                                    <select  class="form-control" name="category" id="category">
                                                        <option value=""></option>
                                                        @foreach(\App\JobCategory::get() as $jobCategory)
                                                            <option @if(request()->category==$jobCategory->id) selected @endif value="{{ $jobCategory->id }}">{{ $jobCategory->name }}</option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                            </div>






                                        <div class="form-group  col-md-2{{ $errors->has('enabled') ? 'has-error' : ''}}">
                                            <label for="enabled" class="control-label">@lang('site.enabled')</label>
                                            <select name="enabled" class="form-control" id="enabled" >
                                                <option></option>
                                                @foreach (json_decode('{"0":"'.__('site.no').'","1":"'.__('site.yes').'"}', true) as $optionKey => $optionValue)
                                                    <option value="{{ $optionKey }}" {{ ((null !== old('enabled',@request()->enabled)) && old('enabled',@request()->enabled) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                                                @endforeach
                                            </select>
                                            {!! clean( $errors->first('enabled', '<p class="help-block">:message</p>') ) !!}
                                        </div>


                                        <div class="col-md-2">

                                            <div class="form-group">
                                                <label for="min_date" class="control-label">@lang('site.min-date')</label>
                                                <input class="form-control date" type="text" value="{{ request()->min_date  }}" name="min_date"/>
                                            </div>

                                        </div>
                                        <div class="col-md-2">

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
                                        <th>#</th><th>@lang('site.name')</th><th>@lang('site.applications')</th>
                                        <th>@lang('site.added-on')</th>
                                        <th>@lang('site.closing-date')</th>
                                        <th>@lang('site.enabled')</th>
                                        <th>@lang('site.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($vacancies as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ( (Request::get('page',1)-1) *$perPage) }}</td>
                                        <td>{{ $item->title }}</td><td>{{ $item->applications()->count() }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($item->created_at)->format('d/M/Y') }}</td>

                                        <td>@if(!empty($item->closes_at))
                                            {{ \Illuminate\Support\Carbon::parse($item->closes_at)->format('d/M/Y') }}
                                            @endif
                                        </td>
                                        <td>{{ boolToString($item->active) }}</td>
                                        <td>
                                            @can('access','view_applications')
                                            <a class="btn btn-success btn-sm" href="{{ route('admin.applications.index',['vacancy'=>$item->id]) }}">@lang('site.applications')({{ $item->applications()->count() }})</a>
                                            @endcan

                                            <div class="btn-group dropup">
                                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ni ni-settings"></i> @lang('site.actions')
                                                </button>
                                                <div class="dropdown-menu">
                                                    <!-- Dropdown menu links -->

                                                    @can('access','view_vacancy')
                                                    <a class="dropdown-item" href="{{ url('/admin/vacancies/' . $item->id) }}">@lang('site.view')</a>
                                                    @endcan

                                                    @can('access','edit_vacancy')
                                                    <a class="dropdown-item" href="{{ url('/admin/vacancies/' . $item->id . '/edit') }}">@lang('site.edit')</a>
                                                    @endcan

                                                    @can('access','delete_vacancy')
                                                    <a class="dropdown-item" href="#" onclick="$('#deleteForm{{ $item->id }}').submit()">@lang('site.delete')</a>
                                                    @endcan



                                                </div>
                                            </div>

                                            <form  onsubmit="return confirm(&quot;@lang('site.confirm-delete')&quot;)"   id="deleteForm{{ $item->id }}"  method="POST" action="{{ url('/admin/vacancies' . '/' . $item->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                                                {{ method_field('DELETE') }}
                                                {{ csrf_field() }}
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! clean( $vacancies->appends(request()->input())->render() ) !!} </div>
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
