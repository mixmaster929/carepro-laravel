@extends('layouts.admin-page')

@section('pageTitle',__('amenu.order-form'))
@section('page-title',__('site.form-section').': '.$orderfieldgroup->name)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >

                        <a href="@route('admin.order-field-groups.index',['orderForm'=>$orderfieldgroup->orderForm->id])" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        <a href="{{ url('/admin/order-field-groups/' . $orderfieldgroup->id . '/edit') }}" ><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>

                        <form method="POST" action="{{ url('admin/order-field-groups' . '/' . $orderfieldgroup->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
                        <br/>
                        <br/>

                        <ul class="list-group">
                            <li class="list-group-item active">@lang('site.id')</li>
                            <li class="list-group-item">{{ $orderfieldgroup->id }}</li>
                            <li class="list-group-item active">@lang('site.name')</li>
                            <li class="list-group-item">{{ $orderfieldgroup->name }}</li>
                            <li class="list-group-item active">@lang('site.sort-order')</li>
                            <li class="list-group-item">{{ $orderfieldgroup->sort_order }}</li>
                            <li class="list-group-item active">@lang('site.description')</li>
                            <li class="list-group-item">{!! clean( $orderfieldgroup->description ) !!}</li>
                            <li class="list-group-item active">@lang('site.layout')</li>
                            <li class="list-group-item">{{ ($orderfieldgroup->layout=='v')? __('site.vertical'):__('site.horizontal') }}</li>
                            <li class="list-group-item active">@lang('site.order-form')</li>
                            <li class="list-group-item">{{ $orderfieldgroup->orderForm->name }}</li>

                        </ul>



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
