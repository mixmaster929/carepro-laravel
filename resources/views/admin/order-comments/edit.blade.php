@extends('layouts.admin-page')

@section('pageTitle',__('site.edit').' '.__('site.comment'))
@section('page-title',$ordercomment->order->user->name.'('.\Illuminate\Support\Carbon::parse($ordercomment->order->created_at)->format('d/M/Y').')')


@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >
                        <a href="{{ route('admin.order-comments.index',['order'=>$ordercomment->order->id]) }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        <br />
                        <br />



                        <form method="POST" action="{{ url('/admin/order-comments/' . $ordercomment->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}

                            @include ('admin.order-comments.form', ['formMode' => 'edit'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('admin.order-comments.form-include')