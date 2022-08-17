<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="title" class="control-label">@lang('site.name')</label>
    <input required  class="form-control" name="name" type="text" id="name" value="{{ old('name',isset($attachment->name) ? $attachment->name : '') }}" >
    {!! clean( $errors->first('name', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="form-group {{ $errors->has('file') ? 'has-error' : ''}}">
    <label for="file" class="control-label">@lang('site.file')</label>

    <input class="form-control"  type="file" name="file"/>
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>
