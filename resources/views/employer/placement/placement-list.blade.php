<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>@lang('site.candidate')</th>
            <th>@lang('site.start-date')</th><th>@lang('site.end-date')</th>
            <th>@lang('site.comments')</th>
            <th>@lang('site.active')</th><th>@lang('site.actions')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($employments as $item)
            <tr>
                <td>{{ $loop->iteration + ( (Request::get('page',1)-1) * $perPage) }}</td>
                <td>{{ $item->candidate->user->name }}</td>
                <td>{{ \Illuminate\Support\Carbon::parse($item->start_date)->format('d/M/Y') }}</td><td>
                    @if(!empty($item->end_date))
                        {{ \Illuminate\Support\Carbon::parse($item->end_date)->format('d/M/Y') }}
                    @endif
                </td>
                <td>{{ $item->employmentComments()->count() }}</td>
                <td>{{ boolToString($item->active) }}</td>
                <td>

                    <a class="btn btn-primary rounded" href="{{ route('employer.view-placement',['employment'=>$item->id]) }}"><i class="fa fa-eye"></i> @lang('site.view')</a>


                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>