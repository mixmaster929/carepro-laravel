<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="name" class="control-label">@lang('site.name')</label>
    <input required class="form-control" name="name" type="text" id="name" value="{{ old('name',isset($category->name) ? $category->name : '') }}" >
    {!! clean( $errors->first('name', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    <label for="description" class="control-label">@lang('site.description') (@lang('site.optional'))</label>
    <textarea class="form-control" rows="5" name="description" type="textarea" id="description" >{{ old('description',isset($category->description) ? $category->description : '') }}</textarea>
    {!! clean( $errors->first('description', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('sort_order') ? 'has-error' : ''}}">
    <label for="sort_order" class="control-label">@lang('site.sort-order')</label>
    <input class="form-control number" name="sort_order" type="text" id="sort_order" value="{{ old('sort_order',isset($category->sort_order) ? $category->sort_order : '') }}" >
    {!! clean( $errors->first('sort_order', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('public') ? 'has-error' : ''}}">
    <label for="public" class="control-label">@lang('site.public')</label>
    <select name="public" class="form-control" id="public" >
        @foreach (json_decode('{"1":"'.__('site.yes').'","0":"'.__('site.no').'"}', true) as $optionKey => $optionValue)
            <option value="{{ $optionKey }}" {{ ((null !== old('public',@$category->public)) && old('public',@$category->public) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
        @endforeach
    </select>
    {!! clean( $errors->first('public', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>
