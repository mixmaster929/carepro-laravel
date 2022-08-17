<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    <label for="title" class="control-label required">@lang('site.title')</label>
    <input required class="form-control" name="title" type="text" id="title" value="{{ old('title',isset($blogpost->title) ? $blogpost->title : '') }}" >
    {!! clean( check( $errors->first('title', '<p class="help-block">:message</p>')) ) !!}
</div>
<div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
    <label for="content" class="control-label">@lang('site.content')</label>
     <textarea class="form-control" rows="5" name="content"   id="content" >{!!  old('content',isset($blogpost->content) ? $blogpost->content : '') !!}</textarea>


    {!! clean( check( $errors->first('content', '<p class="help-block">:message</p>')) ) !!}
</div>
<div class="form-group {{ $errors->has('publish_date') ? 'has-error' : ''}}">
    <label for="publish_date" class="control-label">@lang('site.published-on')</label>
    <input class="form-control date" name="publish_date" type="text" id="publish_date" value="{{ old('publish_date',isset($blogpost->publish_date) ? $blogpost->publish_date : '') }}" >
    {!! clean( check( $errors->first('publish_date', '<p class="help-block">:message</p>')) ) !!}
</div>
<div class="form-group">
    <label for="categories">@lang('site.categories')</label>
    @if($formMode === 'edit')
        <select multiple name="categories[]" id="categories" class="form-control select2">
            <option></option>
            @foreach(\App\BlogCategory::get() as $category)
                <option  @if( (is_array(old('categories')) && in_array(@$category->id,old('categories')))  || (null === old('categories')  && $blogpost->blogCategories()->where('blog_category_id',$category->id)->first() ))
                    selected
                    @endif
                    value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    @else
        <select  multiple name="categories[]" id="categories" class="form-control select2">
            <option></option>
            @foreach(\App\BlogCategory::get() as $category)
                <option @if(is_array(old('categories')) && in_array(@$category->id,old('categories'))) selected @endif value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    @endif

</div>
<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">@lang('site.enabled')</label>
    <select name="status" class="form-control" id="status" >
    @foreach (json_decode('{"1":"Yes","0":"No"}', true) as $optionKey => $optionValue)
        <option value="{{ $optionKey }}" {{ ((null !== old('status',@$blogpost->status)) && old('blogpost',@$blogpost->status) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
    @endforeach
</select>
    {!! clean( check( $errors->first('status', '<p class="help-block">:message</p>')) ) !!}
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('cover_photo') ? 'has-error' : ''}}">
            <label for="cover_photo" class="control-label">@if($formMode=='edit')@lang('site.change')    @endif @lang('site.cover-image')</label>


            <input class="form-control" name="cover_photo" type="file" id="cover_photo" value="{{ isset($blogpost->cover_photo) ? $blogpost->cover_photo : ''}}" >
            {!! clean( check( $errors->first('cover_photo', '<p class="help-block">:message</p>')) ) !!}
        </div>

    </div>
    <div class="col-md-6">
        @if($formMode==='edit' && !empty($blogpost->cover_photo))

            <div><img src="{{ asset($blogpost->cover_photo) }}" class="thmaxwidth"/></div> <br/>
            <a onclick="return confirm('@lang('site.delete-prompt')')" class="btn btn-danger" href="{{ route('admin.blog.remove-picture',['id'=>$blogpost->id]) }}"><i class="fa fa-trash"></i> @lang('site.delete') @lang('site.cover-image')</a>

        @endif
    </div>
</div>

<div class="form-group {{ $errors->has('meta_title') ? 'has-error' : ''}}">
    <label for="meta_title" class="control-label">@lang('site.meta-title')</label>
    <input class="form-control" name="meta_title" type="text" id="meta_title" value="{{ old('meta_title',isset($blogpost->meta_title) ? $blogpost->meta_title : '') }}" >
    {!! clean( check( $errors->first('meta_title', '<p class="help-block">:message</p>')) ) !!}
</div>
<div class="form-group {{ $errors->has('meta_description') ? 'has-error' : ''}}">
    <label for="meta_description" class="control-label">@lang('site.meta-description')</label>
    <textarea class="form-control" rows="5" name="meta_description" type="textarea" id="meta_description" >{{ old('meta_description',isset($blogpost->meta_description) ? $blogpost->meta_description : '') }}</textarea>
    {!! clean( check( $errors->first('meta_description', '<p class="help-block">:message</p>')) ) !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>
