@extends('layouts.admin-page')
@section('pageTitle',__('site.edit').' '.$smsGateway->gateway_name)

@section('page-title')
    {{ __('site.edit').' '.$smsGateway->gateway_name }}
@endsection



@section('page-content')
    <a href="{{ route('admin.sms-gateways') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
    <br />
    <br />
    <form method="POST" action="{{ route('admin.save-sms-gateway',['smsGateway'=>$smsGateway->id]) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
        {{ csrf_field() }}
        @include($form,$settings)

        <button class="btn btn-primary btn-block btn-lg" type="submit">@lang('site.save')</button>
    </form>


@endsection


@section('footer')
    <script src="{{ asset('themes/main/js/summernote/summernote.min.js') }}"></script>
    <script src="{{ asset('themes/main/js/summernote/summernote-active.js') }}"></script>

@endsection


@section('header')
    <link rel="stylesheet" href="{{ asset('themes/main/css/summernote/summernote.css') }}">
@endsection
