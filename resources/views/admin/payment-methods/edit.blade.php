@extends('layouts.admin-page')

@section('pageTitle',$title)
@section('page-title',$title)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >
                        <a href="{{ route('admin.payment-methods') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        <br />
                        <br />



                        <form method="POST" action="{{ route('admin.payment-methods.update',['paymentMethod'=>$paymentMethod->id]) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">

                            {{ csrf_field() }}
                            @include($form,$settings)

                            <div class="form-group">
                                <label for="method_label">@lang('site.label')</label>
                                <input class="form-control" type="text" name="method_label" value="{{ old('method_label',$paymentMethod->method_label) }}"/>
                            </div>

                            <div class="form-group">
                                <label for="sort_order">@lang('site.sort-order')</label>
                                <input class="form-control" type="text" name="sort_order" value="{{ old('sort_order',$paymentMethod->sort_order) }}"/>
                            </div>



                            <button class="btn btn-primary btn-block" type="submit">@lang('site.save')</button>
                        </form>

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
    <script>
        "use strict";
        $('.select2').select2();
    </script>
@endsection
