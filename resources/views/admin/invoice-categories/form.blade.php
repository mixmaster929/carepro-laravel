<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="name" class="control-label">{{ 'Name' }}</label>
    <input required class="form-control" name="name" type="text" id="name" value="{{ old('name',isset($invoicecategory->name) ? $invoicecategory->name : '') }}" >
    {!! clean( $errors->first('name', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('sort_order') ? 'has-error' : ''}}">
    <label for="sort_order" class="control-label">{{ 'Sort Order' }}</label>
    <input class="form-control number" name="sort_order" type="text" id="sort_order" value="{{ old('sort_order',isset($invoicecategory->sort_order) ? $invoicecategory->sort_order : '') }}" >
    {!! clean( $errors->first('sort_order', '<p class="help-block">:message</p>') ) !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>
