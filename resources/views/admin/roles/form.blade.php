<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="name" class="control-label">@lang('site.name')</label>
    <input class="form-control" name="name" type="text" id="name" value="{{ old('name',isset($role->name) ? $role->name : '') }}" >
    {!! clean( $errors->first('name', '<p class="help-block">:message</p>') ) !!}
</div>

<h1>@lang('site.permissions')</h1>
<div class="accordion" id="accordionExample">

    @foreach(\App\PermissionGroup::orderBy('sort_order')->get() as $group)
    <div class="card">
        <div class="card-header" id="headingThree{{ $group->id }}">
            <h2 class="mb-0">
                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree{{ $group->id }}" aria-expanded="false" aria-controls="collapseThree{{ $group->id }}">
                    @lang('perm.'.$group->name)
                </button>
            </h2>
        </div>
        <div id="collapseThree{{ $group->id }}" class="collapse" aria-labelledby="headingThree{{ $group->id }}" data-parent="#accordionExample">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>@lang('site.permission')</th>
                            <th>@lang('site.enabled')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($group->permissions as $permission)
                        <tr>
                            <td>@lang('perm.'.$permission->name)</td>
                            <td>
                                <input type="checkbox" name="{{ $permission->id }}" value="{{ $permission->id }}"
                                       @if(isset($role) && $role->permissions()->find($permission->id))
                                        checked
                                           @endif
                                        />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    @endforeach

</div>
<br/>
<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>
