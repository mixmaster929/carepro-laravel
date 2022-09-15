<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>@lang('site.name')</th>
                <th>@lang('site.applications')</th>
                <th>@lang('site.added-on')</th>
                <th>@lang('site.closing-date')</th>
                <th>@lang('site.enabled')</th>
                <th>@lang('site.actions')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($vacancies as $item)
            <tr>
                <td>{{ $item->title }}</td><td>{{ $item->applications()->count() }}</td>
                <td>{{ \Illuminate\Support\Carbon::parse($item->created_at)->format('d/M/Y') }}</td>
                <td>@if(!empty($item->closes_at))
                        {{ \Illuminate\Support\Carbon::parse($item->closes_at)->format('d/M/Y') }}
                        @endif
                </td>
                <td>{{ boolToString($item->active) }}</td>
                <td>
                    <a class="btn btn-primary rounded" href="{{ route('employer.applications.index',['vacancy'=>$item->id]) }}"><i class="fa fa-eye"></i> @lang('site.view')</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>