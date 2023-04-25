<div class="table-responsive">
<table class="table">
    <thead>
    <tr>
        <th>#</th>
        <th>@lang('site.order-description')</th>
        <th>@lang('site.region')</th>
        <th>@lang('site.interview-date')</th>
        <th>@lang('site.added-on')</th>
        <th>@lang('site.status')</th>
        <th>@lang('site.shortlist')</th>
        <th>@lang('site.bids')</th>
        <th>@lang('site.actions')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->orderForm? $item->orderForm->name : '' }}</td>
            <td>{{ $item->jobRegion? $item->jobRegion->name : '' }}</td>
            <td>
                @if(!empty($item->interview_date))
                    {{ \Illuminate\Support\Carbon::parse($item->interview_date)->format('d/M/Y') }}
                @endif
            </td>
            <td>{{ \Illuminate\Support\Carbon::parse($item->created_at)->format('d/M/Y') }}</td>
            <td>
                {{ orderStatus($item->status) }}
            </td>
            <td>
                {{ $item->candidates()->count() }}
            </td>
            <td>
                {{ $item->bids->count() }}
            </td>
            <td>
                <a class="btn btn-primary rounded  btn-sm" href="{{ route('employer.view-order',['order'=>$item->id]) }}"><i class="fa fa-eye"></i> @lang('site.view')</a>
                <a class="btn btn-success rounded  btn-sm" href="{{ route('employer.view-bids',['order'=>$item->id]) }}">@lang('site.bids')({{ count($item->bids) }})</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
    </div>