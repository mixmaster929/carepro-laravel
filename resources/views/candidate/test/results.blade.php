@extends($userLayout)

@section('page-title',__('site.test-results'))
@section('breadcrumb')
    <li  class="breadcrumb-item"><a href="{{ route('candidate.tests') }}">@lang('site.tests')</a></li>
    <li class="breadcrumb-item">@lang('site.test-results')</li>
@endsection
@section('content')

    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>@lang('site.date')</th>
                <th>@lang('site.score')</th>
                <th>@lang('site.status')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($results as $userTest)
                <tr>
                    <td>{{ $loop->iteration + ( (Request::get('page',1)-1) * $perPage) }}</td>
                    <td>{{ $userTest->created_at->format('d/M/Y') }}</td>
                    <td>{{ $userTest->score }}%</td>
                    <td>@if($userTest->score >= $userTest->test->passmark )
                        @lang('site.passed')
                    @else
                        @lang('site.failed')
                        @endif
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $results->links() }}
@endsection