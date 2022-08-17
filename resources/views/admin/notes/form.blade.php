<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    <label for="title" class="control-label">@lang('site.title')</label>
    <input required  class="form-control" name="title" type="text" id="title" value="{{ old('title',isset($note->title) ? $note->title : '') }}" >
    {!! clean( $errors->first('title', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
    <label for="content" class="control-label">@lang('site.content')</label>
    <textarea required  class="form-control" rows="5" name="content" type="textarea" id="content" >{!! clean( check(old('content',isset($note->content) ? $note->content : '')) ) !!}</textarea>
    {!! clean( $errors->first('content', '<p class="help-block">:message</p>') ) !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>
