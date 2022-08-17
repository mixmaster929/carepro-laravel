<div class="table-responsive">
<table class="table">
    <thead>
    <tr>
        <th>#</th>
        <th>@lang('site.added-on')</th>
        <th>@lang('site.interview-date')</th>
        <th>@lang('site.status')</th>
        <th>@lang('site.shortlist')</th>
        <th>@lang('site.comments')</th>
        <th>@lang('site.actions')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ \Illuminate\Support\Carbon::parse($item->created_at)->format('d/M/Y') }}</td>
            <td>
                @if(!empty($item->interview_date))
                    {{ \Illuminate\Support\Carbon::parse($item->interview_date)->format('d/M/Y') }}
                @endif
            </td>
            <td>
                {{ orderStatus($item->status) }}
            </td>
            <td>
                {{ $item->candidates()->count() }}
            </td>
            <td>
                {{ $item->orderComments()->count() }}
            </td>
            <td>

                <a class="btn btn-primary rounded  btn-sm" href="{{ route('employer.view-order',['order'=>$item->id]) }}"><i class="fa fa-eye"></i> @lang('site.view')</a>

            </td>
        </tr>
    @endforeach
    </tbody>
</table>
    </div>