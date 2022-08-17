@extends('layouts.admin-page')

@section('pageTitle',__('site.employers'))
@section('page-title',__('site.employment-records').': '.$employment->employer->user->name)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >
                        @can('access','view_employments')
                        <a href="{{ route('admin.employments.browse') }}" ><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        @endcan

                        @can('access','edit_employment')
                        <a href="{{ url('/admin/employments/' . $employment->id . '/edit') }}" ><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>
                        @endcan

                        @can('access','delete_employment')
                        <form method="POST" action="{{ url('admin/employments' . '/' . $employment->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
                        @endcan

                        <br/>
                        <br/>

                        <ul class="list-group">
                            <li class="list-group-item active">@lang('site.candidate')</li>
                            <li class="list-group-item"><a target="_blank" href="{{ route('admin.candidates.show',['candidate'=>$employment->candidate->user->id]) }}">{{ $employment->candidate->user->name }} ({{ $employment->candidate->user->email }})</a></li>
                            <li class="list-group-item active">@lang('site.start-date')</li>
                            <li class="list-group-item">{{ \Illuminate\Support\Carbon::parse($employment->start_date)->format('d/M/Y') }}</li>
                            <li class="list-group-item active">@lang('site.end-date')</li>
                            <li class="list-group-item">{{ \Illuminate\Support\Carbon::parse($employment->end_date)->format('d/M/Y') }}</li>
                            <li class="list-group-item active">@lang('site.active')</li>
                            <li class="list-group-item">{{ boolToString($employment->active) }}</li>
                            <li class="list-group-item active">@lang('site.salary')</li>
                            <li class="list-group-item">{{ price($employment->salary) }}</li>

                        </ul>







                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
