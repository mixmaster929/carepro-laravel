<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    <label for="name" class="control-label">{{ __('site.name') }}</label>
    <input required class="form-control" name="name" type="text" id="name" value="{{ old('name',isset($contract->name) ? $contract->name : '') }}" >
    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group  {{ $errors->has('users') ? 'has-error' : ''}}">

    <label for="users">@lang('site.signatories')</label>

        <select required multiple name="users[]" id="users" class="form-control select2">
            <option></option>
        </select>


    {!! clean( $errors->first('users', '<p class="help-block">:message</p>') ) !!}
</div>

<div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    <label for="description" class="control-label">{{ __('site.description') }} (@lang('site.optional'))</label>
    <textarea class="form-control" rows="5" name="description" type="textarea" id="description" >{{ old('description',isset($contract->description) ? $contract->description : '') }}</textarea>
    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group {{ $errors->has('enabled') ? 'has-error' : ''}}">
    <label for="enabled" class="control-label">{{ __('site.enabled') }}</label>
    <select name="enabled" class="form-control" id="enabled" >
    @foreach (json_decode('{"0":"'.__('site.no').'","1":"'.__('site.yes').'"}', true)  as $optionKey => $optionValue)
        <option value="{{ $optionKey }}" {{ ((null !== old('enabled',@$contract->enabled)) && old('enabled',@$contract->enabled) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
    @endforeach
</select>
    {!! $errors->first('enabled', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>
@section('footer')
    <script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
    <script  type="text/javascript">
        "use strict";
        $('#users').select2({
            placeholder: "@lang('site.search-users')...",
            minimumInputLength: 3,
            ajax: {
                url: '{{ route('admin.users.search') }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        term: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }

        });
    </script>
@endsection
@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
@endsection
