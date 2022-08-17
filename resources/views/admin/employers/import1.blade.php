@extends('admin.employers.import-layout')

@section('page-title',__('site.upload-file'))

    @section('form-content')

        <form enctype="multipart/form-data" action="{{ route('admin.employers.import-upload') }}" method="post">
            <p>@lang('site.import-help-text')</p>
            @csrf
            <div class="form-group">
                <label for="file">@lang('site.file')</label>
                <input required="" type="file" name="file"/>
            </div>

            <button type="submit" class="btn btn-primary">@lang('site.upload')</button>
        </form>

        @endsection