@extends('layouts.admin-page')
@section('pageTitle',__('site.free-trial'))

@section('page-title')
    @lang('site.free-trial')
@endsection


@section('page-content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-payment-inner-st">

                    <div  >
                        <div  >



                            <form class="form-inline_" method="post" action="{{ route('admin.save-trial') }}">
                                @csrf

                                <div class="form-group">
                                    <label for="trial_enabled">@lang('site.enable-trial')</label>
                                    <select class="form-control" name="trial_enabled" id="trial_enabled">
                                        @foreach(['1'=>__('site.yes'),'0'=>__('site.no')] as $key=>$value)
                                            <option value="{{ $key }}" @if(old('trial_enabled',setting('trial_enabled'))==$key) selected @endif >{{ $value }}</option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="form-group options">
                                    <label for="trial_package_duration_id">@lang('site.trial-plan')</label>
                                    <select class="form-control" name="trial_package_duration_id" id="trial_package_duration_id">
                                        <option value=""></option>
                                        @foreach($packages as $package)
                                            <option @if(old('trial_package_duration_id',setting('trial_package_duration_id'))==$package->id) selected @endif value="{{ $package->id }}">{{ $package->package->name }} ({{ ($package->type=='m')? __('site.monthly'):__('site.annual') }}) - {{ price($package->price) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group options">
                                    <label for="trial_days">@lang('site.trial-days')</label>
                                    <input class="form-control number" type="text" name="trial_days" value="{{ old('trail_days',setting('trial_days')) }}"/>
                                </div>



                                <button type="submit" class="btn btn-primary">@lang('admin.save')</button>
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
    <script src="{{ asset('js/trial.js') }}" type="text/javascript"></script>


@endsection

