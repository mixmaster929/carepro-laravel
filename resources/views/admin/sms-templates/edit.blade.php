@extends('layouts.admin-page')

@section('pageTitle',__('site.edit').' '.__('site.sms-template').': '.$smstemplate->name)
@section('page-title',__('site.edit').' '.__('site.sms-template').': '.$smstemplate->name)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >
                        @can('access','view_sms_templates')
                        <a href="{{ url('/admin/sms-templates') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        @endcan
                        <br />
                        <br />



                        <form method="POST" action="{{ url('/admin/sms-templates/' . $smstemplate->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}

                            @include ('admin.sms-templates.form', ['formMode' => 'edit'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
