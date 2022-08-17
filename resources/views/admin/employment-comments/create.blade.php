@extends('layouts.admin-page')

@section('pageTitle',__('site.create-new').' '.__('site.employment-comment'))
@section('page-title',$employment->employer->user->name.'('.__('site.employer').') - '.$employment->candidate->user->name.'('.__('site.candidate').')')

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div >
                        <a href="{{ route('admin.employment-comments.index',['employment'=>$employment->id]) }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        <br />
                        <br />


                        <form method="POST" action="{{ route('admin.employment-comments.store',['employment'=>$employment->id]) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            @include ('admin.employment-comments.form', ['formMode' => 'create'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('admin.employment-comments.form-include')