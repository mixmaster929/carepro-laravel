<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="name" class="control-label">@lang('site.name')</label>
    <input class="form-control" name="name" type="text" id="name" value="{{ old('name',isset($orderfieldgroup->name) ? $orderfieldgroup->name : '') }}" >
    {!! clean( $errors->first('name', '<p class="help-block">:message</p>') ) !!}
</div>



<div class="form-group {{ $errors->has('sort_order') ? 'has-error' : ''}}">
    <label for="sort_order" class="control-label">@lang('site.sort-order')</label>
    <input class="form-control number" name="sort_order" type="text" id="sort_order" value="{{ old('sort_order',isset($orderfieldgroup->sort_order) ? $orderfieldgroup->sort_order : '') }}" >
    {!! clean( $errors->first('sort_order', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    <label for="description" class="control-label">@lang('site.description')</label>
    <textarea class="form-control" rows="5" name="description" type="textarea" id="description" >{{ old('description',isset($orderfieldgroup->description) ? $orderfieldgroup->description : '') }}</textarea>
    {!! clean( $errors->first('description', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="form-group {{ $errors->has('layout') ? 'has-error' : ''}}">
    <label for="layout" class="control-label">@lang('site.layout')</label>
    <select name="layout" class="form-control" id="layout" >
        @foreach (json_decode('{"v":"'.__('site.vertical').'","h":"'.__('site.horizontal').'"}', true) as $optionKey => $optionValue)
            <option value="{{ $optionKey }}" {{ ((null !== old('layout',@$orderfieldgroup->layout)) && old('layout',@$orderfieldgroup->layout) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
        @endforeach
    </select>
    {!! clean( $errors->first('layout', '<p class="help-block">:message</p>') ) !!}
</div>


<div id="column-container" class="form-group {{ $errors->has('columns') ? 'has-error' : ''}}">
    <label for="columns" class="control-label">@lang('site.column-width')</label>

    <select name="columns" class="form-control" id="columns" >
        @foreach (range(1,12) as  $optionValue)
            <option value="{{ $optionValue }}" {{ ((null !== old('columns',@$orderfieldgroup->columns)) && old('columns',@$orderfieldgroup->columns) == $optionValue) ? 'selected' : ''}}>{{ $optionValue }}/12</option>
        @endforeach
    </select>
    {!! clean( $errors->first('columns', '<p class="help-block">:message</p>') ) !!}
</div>



<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>
