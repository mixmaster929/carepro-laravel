@extends('layouts.admin-page-wide')


@section('pageTitle',__('site.test-attempts'))

@section('page-title')
    {{ __('site.test-attempts') }}: {{ $user->name }} ({{ $tests->count() }})



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
                'link'=> route('admin.candidates.index'),
                'page'=>__('site.candidates')
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
                    <div  >

                        <a href="{{ url('/admin/candidates') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>




                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table break">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th >@lang('site.test')</th>
                                    <th>@lang('site.score')</th>
                                    <th>@lang('site.status')</th>
                                    <th>@lang('site.created-on')</th>
                                    <th>@lang('site.actions')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tests as $item)
                                    <tr>
                                        <td>{{ $loop->iteration + ( (Request::get('page',1)-1) *$perPage) }}</td>

                                        <td>{{ $item->test->name }}</td>
                                        <td>
                                            @if(isset($item->score))
                                            {{ $item->score }}%
                                                @endif
                                        </td>
                                        <td>@if($item->score >= $item->test->passmark && $item->test->passmark > 0)
                                                <span class="int_green">@lang('site.passed')</span>
                                            @elseif($item->test->passmark > 0)
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
                            <div class="pagination-wrapper"> {!! clean(  $tests->appends(request()->input())->links() ) !!} </div>
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
