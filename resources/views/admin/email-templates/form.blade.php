<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="name" class="control-label required">{{ 'Name' }}</label>
    <input required class="form-control" name="name" type="text" id="name" value="{{ old('name',isset($emailtemplate->name) ? $emailtemplate->name : '') }}" >
    {!! clean( $errors->first('name', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('subject') ? 'has-error' : ''}}">
    <label for="subject" class="control-label">{{ 'Subject' }}</label>
    <input class="form-control" name="subject" type="text" id="subject" value="{{ old('subject',isset($emailtemplate->subject) ? $emailtemplate->subject : '') }}" >
    {!! clean( $errors->first('subject', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('message') ? 'has-error' : ''}}">
    <label for="message" class="control-label">{{ 'Message' }}</label>
    <textarea class="form-control" rows="5" name="message" type="textarea" id="message" >{{ old('message',isset($emailtemplate->message) ? $emailtemplate->message : '') }}</textarea>
    {!! clean( $errors->first('message', '<p class="help-block">:message</p>') ) !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>
