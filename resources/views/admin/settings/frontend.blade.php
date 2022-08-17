@extends('layouts.admin-page')
@section('pageTitle',__('site.disable-frontend'))

@section('page-title')
    @lang('site.frontend')
@endsection


@section('page-content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="product-payment-inner-st">

                    <div  >
                        <div  >



                            <form class="form-inline_" method="post" action="{{ route('admin.save-frontend') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="config_language">@lang('site.status')</label>
                                    <select class="form-control" name="status" id="frontend_status">
                                        @foreach($options as $key=>$value)
                                            <option @if(old('status',$status)==$key) selected @endif value="{{ $key }}">{{ $value }}</option>
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

