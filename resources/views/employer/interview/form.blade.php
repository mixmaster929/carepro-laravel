<div class="form-group {{ $errors->has('user_id') ? 'has-error' : ''}}">
    <label for="user_id" class="control-label required"><span class="req">*</span>@lang('site.employer')</label>
    <input readonly class="form-control" name="user" type="text" id="user" value="{{ $employer->name }}">
    <input class="form-control" name="user_id" type="hidden" id="user_id" value="{{ $employer->id }}">

    @if ($formMode === 'edit')
    <input class="form-control" name="application_id" type="hidden" id="application_id" value="{{ isset($application)? $application->id : 0 }}">
    @else
    <input class="form-control" name="application_id" type="hidden" id="application_id" value="{{ $application->id }}">
    @endif
</div>

<div class="form-group  {{ $errors->has('candidate_id') ? 'has-error' : ''}}">
    <label for="candidate_id">@lang('site.candidates')</label>
    @if($formMode === 'edit')
        <select multiple name="candidate_id[]" id="candidate_id" class="form-control select2">
            @foreach($interview->candidates as $candidate)
                <option  @if( (is_array(old('candidates')) && in_array(@$candidate->id,old('candidates')))  || (null === old('candidates')))
                    selected
                    @endif
                    value="{{ $candidate->id }}">{{ $candidate->user->name }}</option>
            @endforeach
        </select>
    @else
    <input type="text" readonly  name="candidate" id="candidate" class="form-control" value="{{ \App\User::find($application->user_id)->name }}" />
    <input type="hidden" name="candidate_id" id="candidate_id" class="form-control" value="{{ \App\User::find($application->user_id)->candidate->id }}" />
    @endif
</div>

<div class="form-group {{ $errors->has('interview_date') ? 'has-error' : ''}}">
    <label for="interview_date" class="control-label required">@lang('site.interview-date')</label>
    <input required class="form-control date" name="interview_date" type="text" id="interview_date" value="{{ old('interview_date',isset($interview->interview_date) ? $interview->interview_date : '') }}" >
    {!! clean( $errors->first('interview_date', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('interview_time') ? 'has-error' : ''}}">
    <label for="interview_time" class="control-label\">@lang('site.time')</label>
    <input  class="form-control time" name="interview_time" type="text" id="interview_time" value="{{ old('interview_time',isset($interview->interview_time) ? $interview->interview_time : '') }}" >
    {!! clean( $errors->first('interview_time', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('venue') ? 'has-error' : ''}}">
    <label for="venue" class="control-label">@lang('site.venue')</label>
    <textarea class="form-control" rows="5" name="venue" type="textarea" id="venue" >{{ old('venue',isset($interview->venue) ? $interview->venue : '') }}</textarea>
    {!! clean( $errors->first('venue', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('internal_note') ? 'has-error' : ''}}">
    <label for="internal_note" class="control-label">@lang('site.internal-note')</label>
    <textarea class="form-control" rows="5" name="internal_note" type="textarea" id="internal_note" >{{ old('internal_note',isset($interview->internal_note) ? $interview->internal_note : '') }}</textarea>
    {!! clean( $errors->first('internal_note', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('employer_comment') ? 'has-error' : ''}}">
    <label for="employer_comment" class="control-label">@lang('site.employer-comment')</label>
    <textarea class="form-control" rows="5" name="employer_comment" type="textarea" id="employer_comment" >{{ old('employer_comment',isset($interview->employer_comment) ? $interview->employer_comment : '') }}</textarea>
    {!! clean( $errors->first('employer_comment', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('reminder') ? 'has-error' : ''}}">
    <label for="reminder" class="control-label">@lang('site.send-reminder-employee')</label>
    <select name="reminder" class="form-control" id="reminder" >
    @foreach (json_decode('{"1":"Yes","0":"No"}', true) as $optionKey => $optionValue)
        <option value="{{ $optionKey }}" {{ ((null !== old('reminder',@$interview->reminder)) && old('reminder',@$interview->reminder) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
    @endforeach
</select>
    {!! clean( $errors->first('reminder', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('feedback') ? 'has-error' : ''}}">
    <label for="feedback" class="control-label">@lang('site.request-feedback')</label>
    <select name="feedback" class="form-control" id="feedback" >
    @foreach (json_decode('{"1":"Yes","0":"No"}', true) as $optionKey => $optionValue)
        <option value="{{ $optionKey }}" {{ ((null !== old('feedback',@$interview->feedback)) && old('feedback',@$interview->feedback) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
    @endforeach
    </select>
    {!! clean( $errors->first('feedback', '<p class="help-block">:message</p>') ) !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>
