@extends($userLayout)
@section('pageTitle',__('site.cart'))
@section('page-title',__('site.cart'))
@section('content')
    <div class="card-box">
        <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>@lang('site.invoice-no')</th>
                <th>@lang('site.item')</th>
                <th>@lang('site.amount')</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>#{{ $invoice->id }} </td>
                <td>{{ $description }}          @if($invoice->orders()->exists())
                        <a target="_blank" href="{{ route('employer.view-order',['order'=>$invoice->orders()->first()->id]) }}">(@lang('site.order') #{{ $invoice->orders()->first()->id }})</a>
                    @endif</td>
                <td>{!! clean( price($invoice->amount,$invoice->currency_id) ) !!}</td>
                <td><a  title="@lang('site.delete')"  href="{{ route('user.invoice.cancel') }}"><i class="fa fa-trash"></i></a></td>
            </tr>


            </tbody>
        </table>
        </div>
        <form action="{{ route('user.invoice.set-method') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-4 offset-md-8">
                    <br/>
                    <ul class="list-group">
                        <li class="list-group-item active">@lang('site.payment-methods')</li>
                        @foreach($paymentMethods as $method)
                        <li class="list-group-item int_pdlforty" >

                            <input class="form-check-input" type="radio" name="method" id="method{{ $method->id }}" value="{{ $method->id }}">
                            <label  class="form-check-label int_hpw" for="method{{ $method->id }}">
                                {{ $method->method_label }}
                            </label>


                        </li>
                            @endforeach

                    </ul>
                    <br/>
                </div>
            </div>
            <button class="btn rounded btn-primary btn-lg float-right">@lang('site.proceed-payment')</button>
        </form>

    </div>
    @endsection
