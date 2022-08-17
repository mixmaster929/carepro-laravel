@extends('layouts.admin-page')

@section('pageTitle',__('site.create-new').' '.__('site.sms-template'))
@section('page-title',__('site.create-new').' '.__('site.sms-template'))

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div >
                        @can('access','view_sms_templates')
                        <a href="{{ url('/admin/sms-templates') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        @endcan
                        <br />
                        <br />


                        <form method="POST" action="{{ url('/admin/sms-templates') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            @include ('admin.sms-templates.form', ['formMode' => 'create'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
