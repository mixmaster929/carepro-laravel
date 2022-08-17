@lang('site.interview-mail-intro',['name'=>$interview->user->name])<br/>

<strong>@lang('site.date'):</strong> {{ \Illuminate\Support\Carbon::parse($interview->interview_date)->format('d/M/Y') }}<br/>
@if(!empty($interview->interview_time))
<strong>@lang('site.time'):</strong> {{ $interview->interview_time }} <br/>
@endif
@if(!empty($interview->venue))
<strong>@lang('site.venue'):</strong> {{ $interview->venue }} <br/>
@endif

<br/>
@if($interview->candidates()->count()>0)
    @lang('site.interview-mail-candidates')<br/>
    <ul>
        @foreach($interview->candidates as $candidate)
        <li>{{ $candidate->display_name }} ({{ gender($candidate->gender) }},{{ getAge(\Illuminate\Support\Carbon::parse($candidate->date_of_birth)->timestamp) }})</li>
        @endforeach
    </ul>

@endif

@if(!empty($interview->employer_comment))
    <strong>@lang('site.comment')</strong><br/>
    {!! clean( nl2br(clean($interview->employer_comment)) ) !!}

@endif
