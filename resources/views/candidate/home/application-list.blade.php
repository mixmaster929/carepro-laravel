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
        <?php
            if($application->status == 'pending'){
                $status = 'site.pending';
            }
            if($application->status == 'allow'){
                $status = 'site.allow';
            }
            if($application->status == 'deny'){
                $status = 'site.deny';
            }
            if($application->status == 'Interview Planned'){
                $status = 'site.interview_planned';
            }
            if($application->status == 'Placed'){
                $status = 'site.placed';
            }
        ?>
            <tr>
                <th>{{ $loop->iteration + ( (Request::get('page',1)-1) * $perPage) }}</th>
                <td>{{ $application->vacancy->title }}</td>
                <td>{{ \Illuminate\Support\Carbon::parse($application->interviews()->latest()->first()->interview_date)->format('d/M/Y') }} ({{ \Illuminate\Support\Carbon::parse($application->interviews()->latest()->first()->interview_date)->diffForHumans() }})</td>
                <td>{{ $application->created_at->format('d/M/Y') }}</td>
                <td>@lang($status)</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
