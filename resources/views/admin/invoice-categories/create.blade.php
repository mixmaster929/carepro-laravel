@extends('layouts.admin-page')

@section('pageTitle',__('site.create-new').' '.__('site.invoice-category'))
@section('page-title',__('site.create-new').' '.__('site.invoice-category'))

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div >
                        <a href="{{ url('/admin/invoice-categories') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        <br />
                        <br />


                        <form method="POST" action="{{ url('/admin/invoice-categories') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            @include ('admin.invoice-categories.form', ['formMode' => 'create'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
