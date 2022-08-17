<div class="table-responsive">
    <table class="table-stripped table">
        <thead>
        <tr>
            <th>#</th>
            <th>@lang('site.item')</th>
            <th>@lang('site.amount')</th>
            <th>@lang('site.created-on')</th>
            <th>@lang('site.status')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoices as $invoice)
            <tr>
                <td>#{{ $invoice->id }}</td>
                <td>{{ $invoice->title }}
                    @if($invoice->orders()->exists())
                        <a target="_blank"  href="{{ route('employer.view-order',['order'=>$invoice->orders()->first()->id]) }}">(@lang('site.order') #{{ $invoice->orders()->first()->id }})</a>
                    @endif
                </td>
                <td>{!! clean( price($invoice->amount) ) !!}</td>
                <td>{{ $invoice->created_at->format('d/M/Y') }}</td>
                <td>
                    {{ ($invoice->paid==0)?__('site.unpaid'):__('site.paid') }}
                </td>
                <td>
                    @if($invoice->paid==0)
                        <a class="btn btn-success" href="{{ route('user.billing.pay',['invoice'=>$invoice->id]) }}"><i class="fa fa-money-check"></i> @lang('site.pay-now')</a>
                    @endif
                    <a class="btn btn-primary" href="{{ route('user.billing.invoice',['invoice'=>$invoice->id]) }}"><i class="fa fa-eye"></i> @lang('site.view')</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
