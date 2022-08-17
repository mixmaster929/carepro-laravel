<div class="form-group {{ $errors->has('user_id') ? 'has-error' : ''}}">
    <label for="user_id" class="control-label">@lang('site.candidate')</label>

    <select required  name="user_id" id="user_id" class="form-control">
        <?php
        $userId = old('user_id',isset($employment->candidate->user->id) ? $employment->candidate->user->id : false);
        ?>
        @if($userId)
            <option selected value="{{ $userId }}">{{ \App\User::find($userId)->name }} &lt;{{ \App\User::find($userId)->email }}&gt; </option>
        @endif
    </select>

    {!! clean( $errors->first('user_id', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="form-group {{ $errors->has('start_date') ? 'has-error' : ''}}">
    <label for="start_date" class="control-label">@lang('site.start-date')</label>
    <input required class="form-control date" name="start_date" type="text" id="start_date" value="{{ old('start_date',isset($employment->start_date) ? $employment->start_date : '') }}" >
    {!! clean( $errors->first('start_date', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('end_date') ? 'has-error' : ''}}">
    <label for="end_date" class="control-label">@lang('site.end-date') (@lang('site.optional'))</label>
    <input class="form-control date" name="end_date" type="text" id="end_date" value="{{ old('end_date',isset($employment->end_date) ? $employment->end_date : '') }}" >
    {!! clean( $errors->first('end_date', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group {{ $errors->has('active') ? 'has-error' : ''}}">
    <label for="active" class="control-label">@lang('site.active')</label>
    <select  required name="active" class="form-control" id="active" >
    @foreach (json_decode('{"1":"'.__('site.yes').'","0":"'.__('site.no').'"}', true) as $optionKey => $optionValue)
        <option value="{{ $optionKey }}" {{ ((null !== old('active',@$employment->active)) && old('active',@$employment->active) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
    @endforeach
</select>
    {!! clean( $errors->first('active', '<p class="help-block">:message</p>') ) !!}
</div>
<div class="form-group">
    <label for="salary" class="control-label">@lang('site.salary') (@lang('site.optional'))</label>
    <div class="row">
        <div class="col-md-6 {{ $errors->has('salary') ? 'has-error' : ''}}">
            <input class="form-control digit" name="salary" type="text" id="salary" value="{{ old('salary',isset($employment->salary) ? $employment->salary : '') }}" >
            {!! clean( $errors->first('salary', '<p class="help-block">:message</p>') ) !!}
        </div>
        <div class="col-md-6 {{ $errors->has('salary_type') ? 'has-error' : ''}}">
            <select  required name="salary_type" class="form-control" id="salary_type" >
                @foreach (json_decode('{"m":"'.__('site.per-month').'","a":"'.__('site.per-annum').'"}', true) as $optionKey => $optionValue)
                    <option value="{{ $optionKey }}" {{ ((null !== old('salary_type',@$employment->salary_type)) && old('salary_type',@$employment->salary_type) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                @endforeach
            </select>
            {!! clean( $errors->first('salary_type', '<p class="help-block">:message</p>') ) !!}
        </div>
    </div>

</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>
