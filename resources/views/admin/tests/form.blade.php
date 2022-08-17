<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="name" class="control-label required">@lang('site.name')</label>
    <input required class="form-control" name="name" type="text" id="name" value="{{ old('name',isset($test->name) ? $test->name : '') }}" >
    {!! clean( $errors->first('name', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    <label for="description" class="control-label">@lang('site.description')/@lang('site.instructions')</label>
    <textarea class="form-control" rows="5" name="description" type="textarea" id="description" >{{ old('description',isset($test->description) ? $test->description : '') }}</textarea>
    {!! clean( $errors->first('description', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">@lang('site.status')</label>
    <select name="status" class="form-control" id="status" >
    @foreach (json_decode('{"0":"Disabled","1":"Enabled"}', true) as $optionKey => $optionValue)
        <option value="{{ $optionKey }}" {{ ((null !== old('status',@$test->status)) && old('status',@$test->status) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
    @endforeach
</select>
    {!! clean( $errors->first('status', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('minutes') ? 'has-error' : ''}}">
    <label for="minutes" class="control-label">@lang('site.minutes-allowed')</label>
    <input class="form-control digit" name="minutes" type="text" id="minutes" value="{{ old('minutes',isset($test->minutes) ? $test->minutes : '') }}" >
    {!! clean( $errors->first('minutes', '<p class="help-block">:message</p>') ) !!}
    <p class="minutes">@lang('site.minutes-hint')</p>
</div>
<div class="form-group {{ $errors->has('passmark') ? 'has-error' : ''}}">
    <label for="passmark" class="control-label">@lang('site.passmark') (%)</label>
    <input class="form-control digit" name="passmark" type="text" id="passmark" value="{{ old('passmark',isset($test->passmark) ? $test->passmark : '') }}" >
    {!! clean( $errors->first('passmark', '<p class="help-block">:message</p>') ) !!}
    <p class="help-block">@lang('site.passmark-hint')</p>
</div>
<div class="form-group {{ $errors->has('allow_multiple') ? 'has-error' : ''}}">
    <label for="allow_multiple" class="control-label">@lang('site.allow-multiple')</label>
    <select name="allow_multiple" class="form-control" id="allow_multiple" >
    @foreach (json_decode('{"0":"No","1":"Yes"}', true) as $optionKey => $optionValue)
        <option value="{{ $optionKey }}" {{ ((null !== old('allow_multiple',@$test->allow_multiple)) && old('allow_multiple',@$test->allow_multiple) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
    @endforeach
</select>
    {!! clean( $errors->first('allow_multiple', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('show_result') ? 'has-error' : ''}}">
    <label for="show_result" class="control-label">@lang('site.show-result')</label>
    <select name="show_result" class="form-control" id="show_result" >
    @foreach (json_decode('{"0":"No","1":"Yes"}', true) as $optionKey => $optionValue)
        <option value="{{ $optionKey }}" {{ ((null !== old('show_result',@$test->show_result)) && old('show_result',@$test->show_result) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
    @endforeach
</select>
    {!! clean( $errors->first('show_result', '<p class="help-block">:message</p>') ) !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>
