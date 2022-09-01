@extends($userLayout)

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
                'link'=> route('employer.tests.index'),
                'page'=>__('site.tests')
            ],
            [
                'link'=>'#',
                'page'=>__('site.test-attempts')
            ]
    ]])
@endsection

@section('content')
    <div >
        <div>
            <a href="{{ url('/employer/tests') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
            
            <br/>
            <br/>
            <div class="table">
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
                                <a href="{{ route('employer.test_profiles', ['candidate' => $item->user->candidate->id, 'userTest' => $item]) }}" >{{ $item->user->name }}</a>
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
                                <a href="{{ route('employer.tests.results',['userTest'=>$item->id]) }}"><i class="fa fa-eye"></i> @lang('site.view-results')</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="pagination-wrapper"> {!! clean(  $results->appends(request()->input())->links() ) !!} </div>
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
