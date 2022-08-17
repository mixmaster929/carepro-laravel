@extends('admin.candidates.import-layout')

@section('page-title',__('site.preview'))

@section('form-content')
    <p>@lang('site.preview-text')</p>
    <form action="{{ route('admin.candidates.import-process') }}" method="post">
        @csrf
    <table class="table">
        <thead>
        <tr>
            <th>@lang('site.field')</th>
            <th>@lang('site.value')</th>
        </tr>
        </thead>
        <tbody>
            @foreach($fields as $key=>$value)

                <tr>
                    <td>{{ $columns[$key] }}</td>
                    <td>{{ @$values[$value] }}</td>
                </tr>

                @endforeach
        </tbody>
    </table>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="notify" name="notify">
            <label class="form-check-label" for="notify">
                @lang('site.send-account-details')
            </label>
        </div>
        <br/>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="update" name="update">
            <label class="form-check-label" for="update">
                @lang('site.update-existing-users')
            </label>
        </div>

        <br/>
        <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-play"></i> @lang('site.start-import')</button>
    </form>
@endsection