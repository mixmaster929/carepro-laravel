@extends('layouts.admin-page')
@section('pageTitle',__('site.language'))

@section('page-title')
    @lang('site.set-language')
@endsection


@section('page-content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-payment-inner-st">

                    <div  >
                        <div  >



                            <form class="form-inline_" method="post" action="{{ route('admin.save-language') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="config_language">@lang('site.language')</label>
                                    <select class="form-control" name="config_language" id="sms_max_pages">
                                        @foreach($languages as $value)
                                            <option @if(old('config_language',setting('config_language'))==$value) selected @endif value="{{ $value }}">{{ $controller->languageName($value) }} ({{ $value }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">@lang('site.save')</button>
                            </form>


                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection
@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">

@endsection

@section('footer')
    <script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('js/changeseelect.js') }}"></script>


@endsection

