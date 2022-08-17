<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    <label for="title" class="control-label required">@lang('site.title')</label>
    <input required class="form-control" name="title" type="text" id="title" value="{{ old('title',isset($article->title) ? $article->title : '') }}" >
    {!! clean( $errors->first('title', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
    <label for="content" class="control-label">@lang('site.content')</label>
    <textarea class="form-control" rows="5" name="content" type="textarea" id="content" >{!! old('content',isset($article->content) ? $article->content : '') !!}</textarea>
    {!! clean( $errors->first('content', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="form-group {{ $errors->has('slug') ? 'has-error' : ''}}">
    <label for="slug" class="control-label">@lang('site.slug')</label>
    <input class="form-control" name="slug" type="text" id="slug" value="{{ old('slug',isset($article->slug) ? $article->slug : '') }}" >
    {!! clean( check( $errors->first('slug', '<p class="help-block">:message</p>')) ) !!}
</div>

<div class="form-group {{ $errors->has('meta_title') ? 'has-error' : ''}}">
    <label for="meta_title" class="control-label">@lang('site.meta-title')</label>
    <input class="form-control" name="meta_title" type="text" id="meta_title" value="{{ old('meta_title',isset($article->meta_title) ? $article->meta_title : '') }}" >
    {!! clean( check( $errors->first('meta_title', '<p class="help-block">:message</p>')) ) !!}
</div>
<div class="form-group {{ $errors->has('meta_description') ? 'has-error' : ''}}">
    <label for="meta_description" class="control-label">@lang('site.meta-description')</label>
    <textarea class="form-control" rows="5" name="meta_description" type="textarea" id="meta_description" >{{ old('meta_description',isset($article->meta_description) ? $article->meta_description : '') }}</textarea>
    {!! clean( check( $errors->first('meta_description', '<p class="help-block">:message</p>')) ) !!}
</div>

<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">@lang('site.enabled')</label>
    <select name="status" class="form-control" id="status" >
        @foreach (json_decode('{"1":"Yes","0":"No"}', true) as $optionKey => $optionValue)
            <option value="{{ $optionKey }}" {{ ((null !== old('status',@$article->status)) && old('article',@$article->status) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
        @endforeach
    </select>
    {!! clean( check( $errors->first('status', '<p class="help-block">:message</p>')) ) !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>
