@extends('layouts.admin-page')

@section('pageTitle',__('site.candidate-form').': '.$candidatefield->candidateFieldGroup->name)
@section('page-title',__('site.edit').': '.$candidatefield->name)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >
                        <a href="{{ route('admin.candidate-fields.index',['candidateFieldGroup'=>$candidatefield->candidate_field_group_id]) }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        <br />
                        <br />



                        <form method="POST" action="{{ url('/admin/candidate-fields/' . $candidatefield->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}

                            @include ('admin.candidate-fields.form', ['formMode' => 'edit'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('admin.partials.formlogic')