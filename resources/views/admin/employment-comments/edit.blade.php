@extends('layouts.admin-page')

@section('pageTitle',__('site.edit').' '.__('site.comment'))
@section('page-title',$employmentcomment->employment->employer->user->name.'('.__('site.employer').') - '.$employmentcomment->employment->candidate->user->name.'('.__('site.candidate').')'.' - '.\Illuminate\Support\Carbon::parse($employmentcomment->created_at)->diffForHumans())


@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >
                        <a href="{{ route('admin.employment-comments.index',['employment'=>$employmentcomment->employment->id]) }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        <br />
                        <br />



                        <form method="POST" action="{{ url('/admin/employment-comments/' . $employmentcomment->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}

                            @include ('admin.employment-comments.form', ['formMode' => 'edit'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('admin.employment-comments.form-include')