<div class="form-group {{ $errors->has('user_id') ? 'has-error' : ''}}">
    <label for="user_id" class="control-label">{{ 'User Id' }}</label>
    <input class="form-control" name="user_id" type="text" id="user_id" value="{{ old('user_id',isset($application->user_id) ? $application->user_id : '') }}" >
    {!! clean( $errors->first('user_id', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('cv_path') ? 'has-error' : ''}}">
    <label for="cv_path" class="control-label">{{ 'Cv Path' }}</label>
    <input class="form-control" name="cv_path" type="text" id="cv_path" value="{{ old('cv_path',isset($application->cv_path) ? $application->cv_path : '') }}" >
    {!! clean( $errors->first('cv_path', '<p class="help-block">:message</p>') ) !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>
