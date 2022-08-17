<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="name" class="control-label required">@lang('site.name')</label>
    <input required class="form-control" name="name" type="text" id="name" value="{{ old('name',isset($smstemplate->name) ? $smstemplate->name : '') }}" >
    {!! clean( $errors->first('name', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('message') ? 'has-error' : ''}}">
    <label for="message" class="control-label required">@lang('site.message')</label>
    <textarea required class="form-control" rows="5" name="message" type="textarea" id="message" >{{ old('message',isset($smstemplate->message) ? $smstemplate->message : '') }}</textarea>
    {!! clean( $errors->first('message', '<p class="help-block">:message</p>') ) !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>
