<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>@lang('site.employer')</th>
            <th>@lang('site.start-date')</th><th>@lang('site.end-date')</th>
            <th>@lang('site.salary')</th>
            <th>@lang('site.active')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($employments as $item)
            <tr>
                <td>{{ $loop->iteration + ( (Request::get('page',1)-1) * $perPage) }}</td>
                <td>{{ $item->employer->user->name }}</td>
                <td>{{ \Illuminate\Support\Carbon::parse($item->start_date)->format('d/M/Y') }}</td><td>
                    @if(!empty($item->end_date))
                        {{ \Illuminate\Support\Carbon::parse($item->end_date)->format('d/M/Y') }}
                    @endif
                </td>
                <td>{{ price($item->salary) }}</td>
                <td>{{ boolToString($item->active) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
