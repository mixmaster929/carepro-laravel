<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="name" class="control-label">{{ __('site.name') }}</label>
    <input required class="form-control" name="name" type="text" id="name" value="{{ old('name',isset($contracttemplate->name) ? $contracttemplate->name : '') }}" >
    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
    <label for="content" class="control-label">{{ __('site.content') }}</label>
    <textarea class="form-control" rows="5" name="content" type="textarea" id="content" >{{ old('content',isset($contracttemplate->content) ? $contracttemplate->content : '') }}</textarea>
    {!! $errors->first('content', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>

@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/summernote/summernote-bs4.css') }}">
@endsection

@section('footer')
    <script src="{{ asset('vendor/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('/js/admin/contract-template.js') }}"></script>
@endsection
