<div class="form-group {{ $errors->has('question') ? 'has-error' : ''}}">
    <label for="question" class="control-label required">@lang('site.question')</label>
    <textarea required class="form-control" rows="5" name="question" type="textarea" id="question" >{{ old('question',isset($testquestion->question) ? $testquestion->question : '') }}</textarea>
    {!! clean( $errors->first('question', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('sort_order') ? 'has-error' : ''}}">
    <label for="sort_order" class="control-label">{{ 'Sort Order' }}</label>
    <input class="form-control number" name="sort_order" type="text" id="sort_order" value="{{ old('sort_order',isset($testquestion->sort_order) ? $testquestion->sort_order : '') }}" >
    {!! clean( $errors->first('sort_order', '<p class="help-block">:message</p>') ) !!}
</div>



