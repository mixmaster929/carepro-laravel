@extends('layouts.admin-page-wide')

@section('search-form',route('admin.tests.attempts',['test'=>$test->id]))

@section('pageTitle',__('site.tests'))

@section('page-title')
    {{ __('site.test-attempts') }}: {{ $test->name }} ({{ $results->count() }})



    @if(Request::get('user') && \App\User::find(request()->user))
        : {{ \App\User::find(request()->user)->name }}
    @endif

    @if(Request::get('search'))
        : {{ Request::get('search') }}
    @endif


@endsection
@section('breadcrumb')
    @include('partials.breadcrumb',['crumbs'=>[
            [
                'link'=> route('admin.tests.index'),
                'page'=>__('site.tests')
            ],
            [
                'link'=>'#',
                'page'=>__('site.test-attempts')
            ]
    ]])
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

                        <a href="{{ url('/admin/tests') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>


                        <a data-toggle="collapse" href="#filterCollapse" role="button" aria-expanded="false" aria-controls="filterCollapse" class="btn btn-primary btn-sm" title="@lang('site.filter')">
                            <i class="fa fa-filter" aria-hidden="true"></i> @lang('site.filter')
                        </a>

                        <a  href="{{ route('admin.tests.attempts',['test'=>$test->id]) }}" class="btn btn-info btn-sm" title="@lang('site.reset')">
                            <i class="fa fa-sync" aria-hidden="true"></i> @lang('site.reset')
                        </a>

                        <div class="collapse int_tm20" id="filterCollapse" >
                            <div  >
                                <form action="{{ route('admin.tests.attempts',['test'=>$test->id]) }}" method="get">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="search" class="control-label">@lang('site.search')</label>
                                                <input class="form-control" type="text" value="{{ request()->search  }}" name="search"/>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group {{ $errors->has('user') ? 'has-error' : ''}}">
                                                <label for="user" class="control-label">@lang('site.candidate')</label>
                                                <div>
                                                    <select   name="user" id="user" class="form-control">
                                                        <?php
                                                        $userId = request()->user;
                                                        ?>
                                                        @if($userId)
                                                            <option selected value="{{ $userId }}">{{ \App\User::find($userId)->name }} &lt;{{ \App\User::find($userId)->email }}&gt; </option>
                                                        @endif
                                                    </select>
                                                </div>


                                                {!! clean( $errors->first('user', '<p class="help-block">:message</p>') ) !!}
                                            </div>
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
                                    <div class="row">
                                        <div class="col-md-2">

                                            <div class="form-group">
                                                <label for="min_score" class="control-label">@lang('site.min-score')</label>
                                                <input class="form-control digit" type="text" value="{{ request()->min_score  }}" name="min_score"/>
                                            </div>

                                        </div>
                                        <div class="col-md-2">

                                            <div class="form-group">
                                                <label for="max_score" class="control-label">@lang('site.max-score')</label>
                                                <input class="form-control digit" type="text" value="{{ request()->max_score  }}" name="max_score"/>
                                            </div>

                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group  {{ $errors->has('sort') ? 'has-error' : ''}}">
                                                <label for="sort" class="control-label">@lang('site.sort')</label>
                                                <select name="sort" class="form-control" id="sort" >
                                                    <option></option>
                                                    @foreach (json_decode('{"d":"'.__('site.highest-score').'","a":"'.__('site.lowest-score').'"}', true) as $optionKey => $optionValue)
                                                        <option value="{{ $optionKey }}" {{ ((null !== old('sort',@request()->sort)) && old('sort',@request()->sort) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                                                    @endforeach
                                                </select>
                                                {!! clean( $errors->first('sort', '<p class="help-block">:message</p>') ) !!}
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
                            <table class="table break">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th >@lang('site.candidate')</th>
                                    <th>@lang('site.score')</th>
                                    <th>@lang('site.status')</th>
                                    <th>@lang('site.created-on')</th>
                                    <th>@lang('site.actions')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($results as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ( (Request::get('page',1)-1) *$perPage) }}</td>

                                        <td>

                                            <a href="{{ userLink($item->user) }}" >{{ $item->user->name }} ({{ $item->user->email }})</a>


                                        </td>
                                        <td>{{ $item->score }}% </td>
                                        <td>@if($item->score >= $test->passmark && $test->passmark > 0)
                                        <span class="int_green">@lang('site.passed')</span>
                                            @elseif($test->passmark > 0)
                                         <span class="int_red">@lang('site.failed')</span>
                                                @else
                                                @lang('site.ungraded')
                                        @endif

                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d/M/Y') }}
                                        </td>
                                        <td>


                                            <div class="btn-group dropup">
                                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ni ni-settings"></i> @lang('site.actions')
                                                </button>
                                                <div class="dropdown-menu">




                                                                <!-- Dropdown menu links -->

                                                        <a class="dropdown-item" href="{{ route('admin.tests.results',['userTest'=>$item->id]) }}"><i class="fa fa-eye"></i> @lang('site.view-results')</a>


                                                        @can('access','delete_test_result')
                                                        <a onclick="return confirm('@lang('site.confirm-delete')')" class="dropdown-item" href="{{ route('admin.tests.delete-result',['userTest'=>$item->id]) }}"><i class="fa fa-trash"></i> @lang('site.delete')</a>
                                                        @endcan




                                                </div>
                                            </div>



                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! clean(  $results->appends(request()->input())->links() ) !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('vendor/pickadate/picker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/picker.date.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/picker.time.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/legacy.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/order-search.js') }}"></script>
    <script  type="text/javascript">
"use strict";
        $('#user').select2({
            placeholder: "@lang('site.search-users')...",
            minimumInputLength: 3,
            ajax: {
                url: '{{ route('admin.candidates.search') }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        term: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }

        });

    </script>
@endsection


@section('header')
    @parent
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <link href="{{ asset('vendor/pickadate/themes/default.date.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/pickadate/themes/default.time.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/pickadate/themes/default.css') }}" rel="stylesheet">


@endsection
