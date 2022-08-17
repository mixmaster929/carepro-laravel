<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>@lang('site.invoice')</title>
    <link rel="stylesheet" href="{{ asset('css/admin/invoice.css') }}">
</head>

<body>
<div >
    <table width="100%" border="0" cellspacing="2" cellpadding="2">
        <tr>
            <td width="73%" align="center" valign="top" class="centext">
                <div  >
                    @if(!empty(setting('image_logo')))
                        <img src="{{ asset(setting('image_logo')) }}" class="imgdims" />
                        @endif
                </div>
                <div class="coyname" >{{ setting('general_site_name') }}</div>

            </td>
            <td width="27%" valign="top"><p>{!! clean( nl2br(clean(setting('general_address'))) ) !!}
                </p>
                <p>@lang('site.telephone-o'): {{ setting('general_tel') }}<br />
                    @lang('site.e-mail'): {{ setting('general_contact_email') }} <br />
                    <br />
                </p></td>
        </tr>
    </table>
</div>
<div class="invtext">@lang('site.invoice')</div>
<div class="mb40" >{{ \Illuminate\Support\Carbon::parse($invoice->due_date)->format('M d, Y') }}</div>

<div><strong>@lang('site.bill-to'): {{ $invoice->user->name }}</strong></div>
<div>{{ $invoice->user->email }}</div>
<div>@lang('site.invoice-number'): #{{ $invoice->id }}</div>
@if(!empty($invoice->due_date))
<div>@lang('site.due-date'): {{ \Illuminate\Support\Carbon::parse($invoice->due_date)->format('d/M/Y') }}</div>
@endif
<div class="mb40">@lang('site.status'): @if($invoice->paid==1)@lang('site.paid')@else @lang('site.unpaid')@endif</div>

<div class="bdrtable">
    <table width="100%" border="1" cellspacing="2" cellpadding="2" class="tblbdr">
        <tr>
            <th scope="col">@lang('site.item')</th>
            <th scope="col">@lang('site.unit-price')</th>
            <th scope="col">@lang('site.total')</th>
        </tr>
        <tr>
            <td height="100" align="center" valign="top">{{ $invoice->title }}</td>
            <td align="center" valign="top">{{ price($invoice->amount) }}</td>
            <td align="center" valign="top">{{ price($invoice->amount) }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td align="right">@lang('site.total-due'):</td>
            <td align="center"><strong>{{ price($invoice->amount) }}</strong></td>
        </tr>
    </table>

</div>

<div class="mtmb"><strong>@lang('site.amount-in-words'):
        {{ ucwords(convert_number_to_words($invoice->amount)) }} {{ ucfirst(setting('general_currency_name')) }} @lang('site.only')</strong></div>

<div>
    <h3>@lang('site.description')</h3>
   {{ $invoice->description }}
</div>


</body>
</html>
