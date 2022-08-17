<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    <label for="title" class="control-label required">@lang('site.title')</label>
    <input required class="form-control" name="title" type="text" id="title" value="{{ old('title',isset($vacancy->title) ? $vacancy->title : '') }}" >
    {!! clean( $errors->first('title', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    <label for="description" class="control-label required">@lang('site.job-description')</label>
    <textarea required  class="form-control" rows="5" name="description" type="textarea" id="description" >{{ old('description',isset($vacancy->description) ? $vacancy->description : '') }}</textarea>
    {!! clean( $errors->first('description', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('closes_at') ? 'has-error' : ''}}">
    <label for="closes_at" class="control-label">@lang('site.closing-date')</label>
    <input class="form-control date" name="closes_at" type="text" id="closes_at" value="{{ old('closes_at',isset($vacancy->closes_at) ? $vacancy->closes_at : '') }}" >
    {!! clean( $errors->first('closes_at', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="form-group {{ $errors->has('location') ? 'has-error' : ''}}">
    <label for="location" class="control-label">@lang('site.location')</label>
    <input  class="form-control" name="location" type="text" id="location" value="{{ old('location',isset($vacancy->location) ? $vacancy->location : '') }}" >
    {!! clean( $errors->first('location', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="form-group {{ $errors->has('salary') ? 'has-error' : ''}}">
    <label for="salary" class="control-label">@lang('site.salary')</label>
    <input  class="form-control" name="salary" type="text" id="salary" value="{{ old('salary',isset($vacancy->salary) ? $vacancy->salary : '') }}" >
    {!! clean( $errors->first('salary', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="form-group {{ $errors->has('categories') ? 'has-error' : ''}}">
    <label for="categories">@lang('site.job-categories')</label>
    @if($formMode === 'edit')
        <select multiple name="categories[]" id="categories" class="form-control select2">
            <option></option>
            @foreach(\App\JobCategory::orderBy('name')->get() as $category)
                <option  @if( (is_array(old('categories')) && in_array(@$category->id,old('categories')))  || (null === old('categories')  && $vacancy->jobCategories()->where('id',$category->id)->first() ))
                    selected
                    @endif
                    value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    @else
        <select  multiple name="categories[]" id="categories" class="form-control select2">
            <option></option>
            @foreach(\App\JobCategory::orderBy('name')->get() as $category)
                <option @if(is_array(old('categories')) && in_array(@$category->id,old('categories'))) selected @endif value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    @endif
    {!! clean( $errors->first('categories', '<p class="help-block">:message</p>') ) !!}
</div>





<div class="form-group {{ $errors->has('active') ? 'has-error' : ''}}">
    <label for="active" class="control-label required">@lang('site.enabled')</label>
    <select  required  name="active" class="form-control" id="active" >
        @foreach (json_decode('{"1":"Yes","0":"No"}', true) as $optionKey => $optionValue)
            <option value="{{ $optionKey }}" {{ ((null !== old('active',@$vacancy->active)) && old('active',@$vacancy->active) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
        @endforeach
    </select>
    {!! clean( $errors->first('active', '<p class="help-block">:message</p>') ) !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>
