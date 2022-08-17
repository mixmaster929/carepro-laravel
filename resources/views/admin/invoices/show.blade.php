@extends('layouts.admin-page')

@section('pageTitle',__('site.invoice').' #'.$invoice->id)
@section('page-title',__('site.invoice').' #'.$invoice->id)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >

                        @can('access','view_invoices')
                        <a href="{{ url('/admin/invoices') }}"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                       @endcan
                       @can('access','edit_invoice')
                        <a href="{{ url('/admin/invoices/' . $invoice->id . '/edit') }}" ><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>
                        @endcan

                        @can('access','delete_invoice')
                        <form method="POST" action="{{ url('admin/invoices' . '/' . $invoice->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
                        @endcan
                        <br/>
                        <br/>


                        <ul class="list-group">
                            <li class="list-group-item active">@lang('site.id')</li>
                            <li class="list-group-item">#{{ $invoice->id }}</li>
                            <li class="list-group-item active">@lang('site.user')</li>
                            <li class="list-group-item"><a @if($invoice->user->role_id==2) href="{{ url('/admin/employers/' . $invoice->user_id) }}" @elseif($invoice->user->role_id==3)  href="{{ url('/admin/candidates/' . $invoice->user_id) }}" @endif >{{ $invoice->user->name }} ({{ roleName($invoice->user->role_id) }})</a></li>
                            <li class="list-group-item active">@lang('site.amount')</li>
                            <li class="list-group-item">{{ price($invoice->amount,$invoice->currency_id) }}</li>
                            <li class="list-group-item active">@lang('site.item')</li>
                            <li class="list-group-item">{{ $invoice->title }}</li>
                            <li class="list-group-item active">@lang('site.description')</li>
                            <li class="list-group-item">{!! clean( check(nl2br($invoice->description)) ) !!}</li>
                            <li class="list-group-item active">@lang('site.status')</li>
                            <li class="list-group-item">{{ ($invoice->paid==1)? __('site.paid'):__('site.unpaid') }}</li>
                            @if($invoice->paymentMethod()->exists())

                            <li class="list-group-item active">@lang('site.payment-method')</li>
                            <li class="list-group-item">{{ $invoice->paymentMethod->name }}</li>
                            @endif

                            <li class="list-group-item active">@lang('site.added-on')</li>
                            <li class="list-group-item">{{ \Illuminate\Support\Carbon::parse($invoice->created_at)->format('d/M/Y') }}</li>
                            <li class="list-group-item active">@lang('site.due-date')</li>
                            <li class="list-group-item">{{ \Illuminate\Support\Carbon::parse($invoice->due_date)->format('d/M/Y') }}</li>
                            <li class="list-group-item active">@lang('site.category')</li>
                            <li class="list-group-item">@if($invoice->invoiceCategory()->exists())
                                    {{ $invoice->invoiceCategory->name }}
                                @endif</li>

                        </ul>



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
