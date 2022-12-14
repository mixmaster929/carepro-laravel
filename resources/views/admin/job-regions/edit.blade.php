@extends('layouts.admin-page')

@section('pageTitle',__('site.edit').' '.__('site.job-regions').': '.$jobregions->name)
@section('page-title',__('site.edit').' '.__('site.job-regions').': '.$jobregions->name)

@section('page-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div>
                <div>
                    <a href="{{ url('/admin/job-regions') }}" title="@lang('site.back')"><button
                            class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i>
                            @lang('site.back')</button></a>
                    <br />
                    <br />
                    <form method="POST" action="{{ url('/admin/job-regions/' . $jobregions->id) }}"
                        accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                        {{ method_field('PATCH') }}
                        {{ csrf_field() }}

                        @include ('admin.job-regions.form', ['formMode' => 'edit'])
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection