@extends('layouts.admin-page')

@section('pageTitle',__('site.employer-form'))
@section('page-title',__('site.create-field').': '.$employerFieldGroup->name)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div >
                        <a href="{{ route('admin.employer-fields.index',['employerFieldGroup'=>$employerFieldGroup->id]) }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        <br />
                        <br />


                        <form method="POST" action="{{ route('admin.employer-fields.store',['employerFieldGroup'=>$employerFieldGroup->id]) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            @include ('admin.employer-fields.form', ['formMode' => 'create'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('admin.partials.formlogic')