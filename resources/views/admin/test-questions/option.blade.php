<form action="{{ route('admin.test-options.update',['testOption'=>$testOption->id]) }}" method="post">
    @csrf
<div class="form-group">
    <label for="option">@lang('site.option')</label>
    <input class="form-control" type="text" name="option" value="{{ $testOption->option }}"/>
</div>

<div class="form-group">
    <label for="is_correct">@lang('site.correct-answer')?</label>
    {{ Form::select('is_correct', ['0'=>__('site.no'),'1'=>__('site.yes')], $testOption->is_correct,['class' => 'form-control']) }}
</div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" value="{{ __('site.update') }}">
    </div>

</form>
