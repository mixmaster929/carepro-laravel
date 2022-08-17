@extends('layouts.admin-page')

@section('pageTitle',__('site.employers'))
@section('page-title',__('site.create-new').' '.__('site.employer'))

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div >
                        <a href="{{ url('/admin/employers') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        <br />
                        <br />


                        <form method="POST" action="{{ url('/admin/employers') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            @include ('admin.employers.form', ['formMode' => 'create'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

