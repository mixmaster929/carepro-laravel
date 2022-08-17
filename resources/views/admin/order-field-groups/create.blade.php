@extends('layouts.admin-page')

@section('pageTitle',__('amenu.order-form').': '.$orderForm->name)
@section('page-title',__('site.create-new').' '.__('site.form-section'))

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div >
                        <a href="@route('admin.order-field-groups.index',['orderForm'=>$orderForm->id])" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        <br />
                        <br />


                        <form method="POST" action="@route('admin.order-field-groups.store',['orderForm'=>$orderForm->id])" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            @include ('admin.order-field-groups.form', ['formMode' => 'create'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/summernote/summernote-bs4.css') }}">
@endsection
@section('footer')
    <script src="{{ asset('vendor/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('js/admin/ofg-edit.js') }}"></script>


    @include('admin.partials.order-field-form-logic')
@endsection
