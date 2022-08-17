@extends($userLayout)
@section('pageTitle',__('site.invoices'))
@section('page-title',__('site.invoices'))

@section('content')

<div class="card-box">
    @include('account.billing.invoice-list',['invoices'=>$invoices])
    {{ $invoices->links() }}
</div>

        @endsection