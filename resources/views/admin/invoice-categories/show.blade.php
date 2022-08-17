@extends('layouts.admin-page')

@section('pageTitle',__('site.invoice-category').' :'.$invoicecategory->name)
@section('page-title',__('site.invoice-category').' :'.$invoicecategory->name)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >

                        @can('access','view_invoice_categories')
                        <a href="{{ url('/admin/invoice-categories') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                       @endcan

                        @can('access','edit_invoice_category')
                        <a href="{{ url('/admin/invoice-categories/' . $invoicecategory->id . '/edit') }}" ><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>
                        @endcan

                        @can('access','delete_invoice_category')
                        <form method="POST" action="{{ url('admin/invoice-categories' . '/' . $invoicecategory->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
                        @endcan

                        <br/>
                        <br/>

                        <ul class="list-group">
                            <li class="list-group-item active">@lang('site.id')</li>
                            <li class="list-group-item">{{ $invoicecategory->id }}</li>
                            <li class="list-group-item active">@lang('site.name')</li>
                            <li class="list-group-item">{{ $invoicecategory->name }}</li>
                            <li class="list-group-item active">@lang('site.sort-order')</li>
                            <li class="list-group-item">{{ $invoicecategory->sort_order }}</li>

                        </ul>



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
