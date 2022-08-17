<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="name" class="control-label">@lang('site.name')</label>
    <input required class="form-control" name="name" type="text" id="name" value="{{ old('name',isset($employerfield->name) ? $employerfield->name : '') }}" >
    {!! clean( $errors->first('name', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="form-group {{ $errors->has('type') ? 'has-error' : ''}}">
    <label for="type" class="control-label">@lang('site.type')</label>
    <select required name="type" class="form-control" id="type" >
        <option value=""></option>
        @foreach (json_decode('{"text":"Text","textarea":"Textarea","select":"Select","radio":"Radio","checkbox":"Checkbox","file":"File"}', true) as $optionKey => $optionValue)
            <option value="{{ $optionKey }}" {{ (isset($employerfield->type) && $employerfield->type == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
        @endforeach
    </select>
    {!! clean( $errors->first('type', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('sort_order') ? 'has-error' : ''}}">
    <label for="sort_order" class="control-label">@lang('site.sort-order')</label>
    <input required class="form-control number" name="sort_order" type="text" id="sort_order" value="{{ old('sort_order',isset($employerfield->sort_order) ? $employerfield->sort_order : $sortOrder) }}" >
    {!! clean( $errors->first('sort_order', '<p class="help-block">:message</p>') ) !!}
</div>
<div id="option-container" class="form-group {{ $errors->has('options') ? 'has-error' : ''}}">
    <label for="options" class="control-label">@lang('site.options') </label>
    <textarea placeholder="@lang('site.option-placeholder')" class="form-control" rows="5" name="options" type="textarea" id="options" >{{ isset($employerfield->options) ? $employerfield->options : ''}}</textarea>
    {!! clean( $errors->first('options', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('required') ? 'has-error' : ''}}">
    <label for="required" class="control-label">@lang('site.required')</label>
    <select name="required" class="form-control" id="required" >
        @foreach (json_decode('{"1":"'.__('site.yes').'","0":"'.__('site.no').'"}', true) as $optionKey => $optionValue)
            <option value="{{ $optionKey }}" {{ (isset($employerfield->required) && $employerfield->required == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
        @endforeach
    </select>
    {!! clean( $errors->first('required', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('placeholder') ? 'has-error' : ''}}">
    <label for="placeholder" class="control-label">@lang('site.hint')</label>
    <input class="form-control" name="placeholder" type="text" id="placeholder"  value="{{ isset($employerfield->placeholder) ? $employerfield->placeholder : ''}}" >

    {!! clean( $errors->first('placeholder', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('enabled') ? 'has-error' : ''}}">
    <label for="enabled" class="control-label">@lang('site.enabled')</label>
    <select name="enabled" class="form-control" id="enabled" >
        @foreach (json_decode('{"1":"'.__('site.yes').'","0":"'.__('site.no').'"}', true) as $optionKey => $optionValue)
            <option value="{{ $optionKey }}" {{ (isset($employerfield->enabled) && $employerfield->enabled == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
        @endforeach
    </select>
    {!! clean( $errors->first('enabled', '<p class="help-block">:message</p>') ) !!}
</div>



<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>
