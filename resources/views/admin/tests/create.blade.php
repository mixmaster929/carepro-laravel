@extends('layouts.admin-page')

@section('pageTitle',__('site.tests'))
@section('page-title',__('site.create-new').' '.__('site.test'))

    @section('breadcrumb')
        @include('partials.breadcrumb',['crumbs'=>[
                [
                    'link'=> route('admin.tests.index'),
                    'page'=>__('site.tests')
                ],
                [
                    'link'=>'#',
                    'page'=>__('site.create-test')
                ]
        ]])
    @endsection

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div >
                        @can('access','view_tests')
                        <a href="{{ url('/admin/tests') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        @endcan
                        <br />
                        <br />


                        <form method="POST" action="{{ url('/admin/tests') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            @include ('admin.tests.form', ['formMode' => 'create'])

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

@endsection
