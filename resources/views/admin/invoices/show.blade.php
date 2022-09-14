@extends('layouts.admin-page')

@section('pageTitle',__('site.invoice').' #'.$invoice->id)
@section('page-title',__('site.invoice').' #'.$invoice->id)

@section('page-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <div>
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
                        <a href="javascript:download()" title="@lang('site.pdf')"><button class="btn btn-success btn-sm"><i class="fa fa-download"></i>@lang('site.pdf')</button></a>
                        <br/>
                        <br/>
                        <div class="card card-invoice">
                            <div class="card-body">
                                <div id="print-content" style="padding: 20px;">
                                    <div>
                                        <img src="http://localhost:8000/uploads/settings/avatar.png" width="200" style="float: right;">
                                    </div><br><br>
                                    <div class="invoice-header">
                                        <h1 class="invoice-title">@lang('site.invoice')</h1>
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
                                                <td colspan="2" class="tx-right"><?php echo price($tax) ?></td>
                                            </tr>
                                            <tr>
                                                <td class="tx-right tx-uppercase tx-bold tx-inverse">@lang('site.total')</td>
                                                <td colspan="2" class="tx-right"><h4 class="tx-primary tx-bold"><?php echo price($total) ?></h4></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div><!-- table-responsive -->
                                </div>
                            </div><!-- card-body -->
                        </div><!-- card -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>



<script type="text/javascript">
    function download() {
        var element = document.getElementById('print-content');
        var opt = {
            jsPDF: {
                format: 'a4'
            },
            // pageBreak: { mode: 'css', after:'.break-page'},
            // html2canvas:  { letterRendering: true, useCORS: true,     logging: true},
            margin: 10, //top, left, buttom, right
            image: {type: 'jpeg', quality: 1},
            filename: 'invoice.pdf'
        };
        var worker = html2pdf().set(opt).from(element).toPdf().save('invoice.pdf');
    }
</script>