<table class="table">
    <thead>
    <tr>
        <th>@lang('site.interview-date')</th>
        <th>@lang('site.interview-time')</th>
        <th>@lang('site.candidates')</th>
        <th>@lang('site.actions')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($interviews as $interview)
        <tr>
            <td>{{ \Illuminate\Support\Carbon::parse($interview->interview_date)->format('d/M/Y') }} ({{ \Illuminate\Support\Carbon::parse($interview->interview_date)->diffForHumans() }})</td>
            <td>{{ $interview->interview_time }}</td>
            <td>{{ $interview->application? $interview->application->user->name : "" }}</td>
            <td>
                <a class="btn btn-primary rounded" href="{{ route('employer.view-interview',['interview'=>$interview->id]) }}"><i class="fa fa-eye"></i> @lang('site.view')</a>
                <a class="btn btn-primary rounded" href="{{ route('employer.edit-interview',['interview'=>$interview->id]) }}"><i class="fa fa-edit"></i> @lang('site.edit')</a>
                <a class="btn btn-primary rounded" href="#" onclick="$('#deleteForm{{ $interview->id }}').submit()"><i class="fa fa-trash"></i> @lang('site.delete-interview')</a>
                <form  onsubmit="return confirm(&quot; @lang('site.confirm-delete') &quot;)"   id="deleteForm{{ $interview->id }}"  method="POST" action="{{ url('/employer/interview' . '/' . $interview->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                    {{ method_field('DELETE') }}
                    {{ csrf_field() }}
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>