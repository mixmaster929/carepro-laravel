@extends($userLayout)

@section('page-title')
    {{ __('site.vacancies') }} ({{ $vacancies->count() }})
@endsection

@section('content')
	<div class="table-responsive">
			<table class="table">
					<thead>
							<tr>
									<th>#</th><th>@lang('site.name')</th><th>@lang('site.applications')</th>
									<th>@lang('site.added-on')</th>
									<th>@lang('site.closing-date')</th>
									<th>@lang('site.enabled')</th>
									<th>@lang('site.actions')</th>
							</tr>
					</thead>
					<tbody>
					@foreach($vacancies as $item)
							<tr>
									<td>{{ $loop->iteration + ( (Request::get('page',1)-1) *$perPage) }}</td>
									<td>{{ $item->title }}</td><td>{{ $item->applications()->count() }}</td>
									<td>{{ \Illuminate\Support\Carbon::parse($item->created_at)->format('d/M/Y') }}</td>

									<td>@if(!empty($item->closes_at))
											{{ \Illuminate\Support\Carbon::parse($item->closes_at)->format('d/M/Y') }}
											@endif
									</td>
									<td>{{ boolToString($item->active) }}</td>
									<td>
											<a class="btn btn-success btn-sm" href="{{ route('employer.applications.index',['vacancy'=>$item->id]) }}">@lang('site.applications')({{ $item->applications()->count() }})</a>
											<div class="btn-group dropup">
													<button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															<i class="ni ni-settings"></i> @lang('site.actions')
													</button>
													<div class="dropdown-menu">
															<!-- Dropdown menu links -->
															<a class="dropdown-item" href="{{ url('/employer/vacancies/' . $item->id) }}">@lang('site.view')</a>
															<a class="dropdown-item" href="{{ url('/employer/vacancies/' . $item->id . '/edit') }}">@lang('site.edit')</a>
													</div>
											</div>
									</td>
							</tr>
					@endforeach
					</tbody>
			</table>
			<div class="pagination-wrapper"> {!! clean( $vacancies->appends(request()->input())->render() ) !!} </div>
	</div>
@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('css/admin/vacancies.css') }}">

@endsection