<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>@lang('site.position')</th>
            <th>@lang('site.interview-date')</th>
            <th>@lang('site.application-date')</th>
            <th>@lang('site.status')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($applications as $application)
            <tr>
                <th>{{ $loop->iteration + ( (Request::get('page',1)-1) * $perPage) }}</th>
                <td>{{ $application->vacancy->title }}</td>
                <td>{{ \Illuminate\Support\Carbon::parse($application->interviews()->latest()->first()->interview_date)->format('d/M/Y') }} ({{ \Illuminate\Support\Carbon::parse($application->interviews()->latest()->first()->interview_date)->diffForHumans() }})</td>
                <td>{{ $application->created_at->format('d/M/Y') }}</td>
                <td>{{ $application->status }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
