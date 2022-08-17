@extends('layouts.admin-page')

@section('pageTitle',__('site.create-new').' '.__('site.order-comment'))
@section('page-title',$order->user->name.'('.\Illuminate\Support\Carbon::parse($order->created_at)->format('d/M/Y').')')

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div >
                        <a href="{{ route('admin.order-comments.index',['order'=>$order->id]) }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        <br />
                        <br />


                        <form method="POST" action="{{ route('admin.order-comments.store',['order'=>$order->id]) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            @include ('admin.order-comments.form', ['formMode' => 'create'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('admin.order-comments.form-include')