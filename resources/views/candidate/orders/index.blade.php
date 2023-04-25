@extends($userLayout)

@section('page-title', __('site.orders'))

@section('content')
<div>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>@lang('site.order-description')</th>
                <th>@lang('site.region')</th>
                <th>@lang('site.interview-date')</th>
                <th>@lang('site.added-on')</th>
                <th>@lang('site.status')</th>
                <th>@lang('site.my-bid')</th>
                <th>@lang('site.actions')</th>
            </tr>
        </thead>
        <tbody>
                @foreach($orders as $order)
                <tr>
                    <?php
                        $pivotData = $order->bids()->wherePivot('user_id', Auth::user()->id)->first();
                    ?>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->orderForm->name }}</td>
                    <td>{{ $order->jobRegion->name }}</td>
                    <td>
                        @if(!empty($order->interview_date))
                            {{ \Illuminate\Support\Carbon::parse($order->interview_date)->format('d/M/Y') }}
                        @endif
                    </td>
                    <td>
                        {{ \Illuminate\Support\Carbon::parse($order->created_at)->format('d/M/Y') }}
                    </td>
                    <td>{{ orderStatus($order->status) }}</td>
                    <td>@if($pivotData && ($pivotData->pivot->status == 'allow')) <span class='badge badge-success'>@else <span>@endif {{ $pivotData? '€'.$pivotData->pivot->offer : '' }}</span></td>
                    <td>
                        <a class="btn btn-primary rounded  btn-sm" href="{{ route('candidate.view-order',['order'=>$order->id]) }}"><i class="fa fa-eye"></i> @lang('site.view')</a>
                    </td>
                </tr>
                @endforeach
        </tbody>
    </table>
</div>
@endsection