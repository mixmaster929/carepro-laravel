<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="name" class="control-label">@lang('site.name')</label>
    <input class="form-control" name="name" type="text" id="name" value="{{ old('name',isset($admin->name) ? $admin->name : '') }}" >
    {!! clean( check( $errors->first('name', '<p class="help-block">:message</p>')) ) !!}
</div>
<div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
    <label for="email" class="control-label">@lang('site.email')</label>
    <input class="form-control" name="email" type="text" id="email" value="{{ old('email',isset($admin->email) ? $admin->email : '') }}" >
    {!! clean( check( $errors->first('email', '<p class="help-block">:message</p>')) ) !!}
</div>
<div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
    <label for="password" class="control-label">@if($formMode=='edit') @lang('site.change')  @endif @lang('site.password')</label>
    <input class="form-control" name="password" type="password" id="password" value="{{ old('password') }}" >
    {!! clean( check( $errors->first('password', '<p class="help-block">:message</p>')) ) !!}
</div>
<div class="row">
    <div class="form-group col-md-12">
        <label for="roles">@lang('site.roles')</label>
        @if($formMode === 'edit')
            <select required multiple name="roles[]" id="roles" class="form-control select2">
                <option></option>
                @foreach(\App\AdminRole::get() as $role)
                    <option  @if( (is_array(old('roles')) && in_array(@$role->id,old('roles')))  || (null === old('roles')  && $admin->adminRoles()->where('id',$role->id)->first() ))
                        selected
                        @endif
                        value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        @else
            <select required multiple name="roles[]" id="roles" class="form-control select2">
                <option></option>
                @foreach(\App\AdminRole::get() as $role)
                    <option @if(is_array(old('roles')) && in_array(@$role->id,old('roles'))) selected @endif value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        @endif
    </div>
</div>
<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    <label for="status" class="control-label">@lang('site.enabled')</label>
    <select name="status" class="form-control" id="status" >
    @foreach (json_decode('{"1":"'.__('site.yes').'","0":"'.__('site.no').'"}', true) as $optionKey => $optionValue)
        <option value="{{ $optionKey }}" {{ ((null !== old('status',@$admin->status)) && old('admin',@$admin->status) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
    @endforeach
</select>
    {!! clean( check( $errors->first('status', '<p class="help-block">:message</p>')) ) !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>
