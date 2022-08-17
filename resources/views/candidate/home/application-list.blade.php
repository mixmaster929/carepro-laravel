<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>@lang('site.position')</th>
            <th>@lang('site.application-date')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($applications as $application)
            <tr>
                <th>{{ $loop->iteration + ( (Request::get('page',1)-1) * $perPage) }}</th>
                <td>{{ $application->vacancy->title }}</td>
                <td>{{ $application->created_at->format('d/M/Y') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
