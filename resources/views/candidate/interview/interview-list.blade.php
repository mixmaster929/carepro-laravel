<table class="table">
    <thead>
    <tr>
        <th>@lang('site.interview-date')</th>
        <th>@lang('site.interview-time')</th>
        <th>@lang('site.employer')</th>
        <th>@lang('site.actions')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($interviews as $interview)
        <tr>
            <td>{{ \Illuminate\Support\Carbon::parse($interview->interview_date)->format('d/M/Y') }} ({{ \Illuminate\Support\Carbon::parse($interview->interview_date)->diffForHumans() }})</td>
            <td>{{ $interview->interview_time }}</td>
            <td>{{ $interview->user->name }}</td>
            <td>
                <a class="btn btn-primary rounded" href="{{ route('candidate.view-interview',['interview'=>$interview->id]) }}"><i class="fa fa-eye"></i> @lang('site.view')</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>