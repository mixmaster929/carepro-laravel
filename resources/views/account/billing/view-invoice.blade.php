@extends($userLayout)
@section('page-title',__('site.invoice').' #'.$invoice->id)
@section('breadcrumb')
    <li  class="breadcrumb-item"><a href="{{ url('/account/billing/invoices') }}">@lang('site.invoices')</a></li>
    <li class="breadcrumb-item">@lang('site.view')</li>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">

            <a href="{{ url('/account/billing/invoices') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
            <a href="javascript:printDiv('print-content')"><button class="btn btn-success btn-sm"><i class="fa fa-print"></i></button></a>
            <a href="javascript:window.print()" title="@lang('site.download')"><button class="btn btn-danger btn-sm"><i class="fa fa-download"></i>@lang('site.download')</button></a>

            <br />
            <br />
            <div class="card card-invoice">
                <div class="card-body">
                    <div id="print-content">
                        <div class="invoice-header">
                            <h1 class="invoice-title"><img src="https://dailycargo.nl/portal/public/uploads/settings/nnH9az4OmCIAuVDBJRWgyPTZSjVpyt6J8s8941bh.png" width="200">@lang('site.invoice')</h1>
                            <div class="billed-from">
                                <h6>{{ setting('general_site_name') }}</h6>
                                <p>{{ setting('general_address') }}<br>
                                    @lang('site.telephone'): {{ setting('general_tel') }}<br>
                                    @lang('site.email'): {{ setting('general_contact_email') }}<br>
                                    @lang('settings.general_tax_number'): {{ setting('general_tax_number') }}<br>
                                    @lang('settings.general_chamber_of_commerce'): {{ setting('general_chamber_of_commerce') }}<br>
                                </p>
                            </div><!-- billed-from -->
                        </div><!-- invoice-header -->

                        <div class="row mg-t-20">
                            <div class="col-md">
                                <label class="tx-gray-600">@lang('site.billed-to')</label>
                                <div class="billed-to">
                                    @if($address)
                                        <h6>{{ $address->name }}</h6>
                                        @else
                                            <h6>{{ $invoice->user->name }}</h6>
                                    @endif
                                    <p>
                                        @if($address)
                                            {{ $address->address }}<br>
                                            {{ $address->address2 }}<br>
                                            @lang('site.city'): {{ $address->city }}<br>
                                            @lang('site.zip'): {{ $address->zip }}<br>
                                            @lang('site.telephone'): {{ $address->phone }}<br>
                                        @endif
                                        @lang('site.email'): {{ $invoice->user->email }}</p>
                                </div>
                            </div><!-- col -->
                            <div class="col-md">
                                <label class="tx-gray-600">@lang('site.invoice-information')</label>
                                <p class="invoice-info-row">
                                    <span>@lang('site.invoice-no')</span>
                                    <span>{{ $invoice->id }}</span>
                                </p>
                                @if($invoice->paid==1 && $invoice->paymentMethod()->exists())
                                    <p class="invoice-info-row">
                                        <span>@lang('site.payment-method')</span>
                                        <span>{{ $invoice->paymentMethod->name }}</span>
                                    </p>
                                @endif
                                <p class="invoice-info-row">
                                    <span>@lang('site.status'):</span>
                                    <span>{{ ($invoice->paid==0)?__('site.unpaid'):__('site.paid') }}</span>
                                </p>
                                <p class="invoice-info-row">
                                    <span>@lang('site.issue-date'):</span>
                                    <span>{{ $invoice->created_at->format('M d, Y') }}</span>
                                </p>
                                <p class="invoice-info-row">
                                    <span>@lang('site.due-date'):</span>
                                    <span>{{ \Illuminate\Support\Carbon::parse($invoice->due_date)->format('M d, Y') }}</span>
                                </p>
                            </div><!-- col -->
                        </div><!-- row -->

                        <div class="table-responsive mg-t-40">
                            <table class="table table-invoice">
                                <thead>
                                <tr>
                                    <th class="wd-40p">@lang('site.item')</th>
                                    <th class="tx-center">@lang('site.quantity')</th>
                                    <th class="tx-right">@lang('site.unit-cost')</th>
                                    <th class="tx-right">@lang('site.total')</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="tx-12">{{ $invoice->title }}</td>
                                    <td class="tx-center">1</td>
                                    <td class="tx-right">{{ price($invoice->amount) }}</td>
                                    <td class="tx-right">{{ price($invoice->amount) }}</td>
                                </tr>

                                <tr>
                                    <td colspan="2" rowspan="4" class="valign-middle">
                                        <div class="invoice-notes">
                                            <label class="az-content-label tx-13">@lang('site.notes')</label>
                                            <p>{{ $invoice->description }}</p>
                                        </div><!-- invoice-notes -->
                                    </td>
                                    <td class="tx-right">@lang('site.sub-total')</td>
                                    <td colspan="2" class="tx-right">{{ price($invoice->amount) }}</td>
                                </tr>
                                <tr>
                                    <td class="tx-right">{{setting('general_vat')}}@lang('settings.general_vat')</td>
                                    <?php
                                        $vat = number_format(setting('general_vat'));
                                        $amount = number_format($invoice->amount);
                                        $tax = number_format($vat * $amount /100, 2);
                                        $total = number_format(($tax+$amount), 2);
                                    ?>
                                    <td colspan="2" class="tx-right"><?php echo price($tax); ?></td>
                                </tr>
                                <tr>
                                    <td class="tx-right tx-uppercase tx-bold tx-inverse">@lang('site.total')</td>
                                    <td colspan="2" class="tx-right"><h4 class="tx-primary tx-bold"><?php echo price($total); ?></h4></td>
                                </tr>
                                </tbody>
                            </table>
                        </div><!-- table-responsive -->
                    </div>
                    <hr class="mg-b-40">
                    @if($invoice->paid==0)
                    <a href="{{ route('user.billing.pay',['invoice'=>$invoice->id]) }}" class="btn btn-primary btn-block">@lang('site.pay-now')</a>
                    @endif
                </div><!-- card-body -->
            </div><!-- card -->


        </div>

    </div>
@endsection

@section('content-class','az-content-body-invoice')
