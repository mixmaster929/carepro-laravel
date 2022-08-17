@extends('layouts.admin-page')

@section('pageTitle',__('site.candidate-form'))
@section('page-title',__('site.create-field').': '.$candidateFieldGroup->name)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div >
                        <a href="{{ route('admin.candidate-fields.index',['candidateFieldGroup'=>$candidateFieldGroup->id]) }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        <br />
                        <br />


                        <form method="POST" action="{{ route('admin.candidate-fields.store',['candidateFieldGroup'=>$candidateFieldGroup->id]) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            @include ('admin.candidate-fields.form', ['formMode' => 'create'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('admin.partials.formlogic')