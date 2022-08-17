@extends('layouts.admin-page')

@section('pageTitle',__('site.interview').': '.$interview->user->name)
@section('page-title',__('site.interview').': '.$interview->user->name)

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >
                        @can('access','view_interviews')
                        <a href="{{ url('/admin/interviews') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                       @endcan

                        @can('access','edit_interview')
                        <a href="{{ url('/admin/interviews/' . $interview->id . '/edit') }}"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>
                        @endcan

                        @can('access','delete_interview')
                        <form method="POST" action="{{ url('admin/interviews' . '/' . $interview->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
                        @endcan

                        <br/>
                        <br/>

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#home">@lang('site.details')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#menu1">@lang('site.candidates')</a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane container active" id="home">
                                <br/>


                                <ul class="list-group">
                                    <li class="list-group-item active">@lang('site.id')</li>
                                    <li class="list-group-item">{{ $interview->id }}</li>
                                    <li class="list-group-item active">@lang('site.employer')</li>
                                    <li class="list-group-item"><a target="_blank" href="{{ userLink($interview->user) }}">{{ $interview->user->name }}</a></li>
                                    <li class="list-group-item active">@lang('site.interview-date')</li>
                                    <li class="list-group-item">{{ \Illuminate\Support\Carbon::parse($interview->interview_date)->toDateString() }}</li>
                                    <li class="list-group-item active">@lang('site.time')</li>
                                    <li class="list-group-item">{{ $interview->interview_time }}</li>
                                    <li class="list-group-item active">@lang('site.venue')</li>
                                    <li class="list-group-item">{!! clean( nl2br(clean($interview->venue)) ) !!}</li>
                                    <li class="list-group-item active">@lang('site.internal-note')</li>
                                    <li class="list-group-item">{!! clean( nl2br(clean($interview->internal_note)) ) !!}</li>
                                    <li class="list-group-item active">@lang('site.employer-comment')</li>
                                    <li class="list-group-item">{!! clean( nl2br(clean($interview->employer_comment)) ) !!}</li>
                                    <li class="list-group-item active">@lang('site.employer-feedback')</li>
                                    <li class="list-group-item">{!! clean( nl2br(clean($interview->employer_feedback)) ) !!}</li>

                                </ul>

                            </div>
                            <div class="tab-pane container fade" id="menu1">
                                <br/>

                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>@lang('site.picture')</th>
                                            <th>@lang('site.name')</th>
                                            <th>@lang('site.age')</th>
                                            <th>@lang('site.telephone')</th>
                                            <th>@lang('site.email')</th>
                                            <th>@lang('site.actions')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($interview->candidates as $item)
                                            <tr>
                                                <td>{{ $loop->iteration  }}</td>
                                                <td>
                                                    <div class="media align-items-center">
              <span class="avatar avatar-sm rounded-circle">
                  @if(!empty($item->picture) && file_exists($item->picture))
                      <img   src="{{ asset($item->picture) }}">
                  @else
                      <img   src="{{ asset('img/man.jpg') }}">
                  @endif

              </span>
                                                    </div>


                                                </td>
                                                <td>{{ $item->user->name }}</td>
                                                <td>{{  getAge(\Illuminate\Support\Carbon::parse($item->date_of_birth)->timestamp) }}</td>
                                                <td>{{ $item->user->telephone }}</td>
                                                <td>{{ $item->user->email }}</td>
                                                <td>
                                                    <div class="btn-group dropup">
                                                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="ni ni-settings"></i> @lang('site.actions')
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <!-- Dropdown menu links -->
                                                            @can('access','view_candidate')
                                                            <a class="dropdown-item" href="{{ url('/admin/candidates/' . $item->user->id) }}">@lang('site.view')</a>
                                                            @endcan



                                                            @can('access','view_candidate')
                                                            <div class="dropdown-divider"></div>
                                                            <h6 class="dropdown-header">@lang('site.download-profile')</h6>
                                                            <a class="dropdown-item" href="{{ route('admin.candidate.download',['id'=>$item->user->id,'full'=>1]) }}">@lang('site.full-profile')</a>
                                                            <a class="dropdown-item" href="{{ route('admin.candidate.download',['id'=>$item->user->id,'full'=>0]) }}">@lang('site.partial-profile')</a>
                                                            @endcan


                                                        </div>
                                                    </div>

                                                    <div class="btn-group dropup">
                                                        <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fa fa-book"></i> @lang('site.records')
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            @can('access','view_candidate_notes')
                                                            <a class="dropdown-item" href="{{ route('admin.notes.index',['user'=>$item->user->id]) }}">@lang('site.notes') ({{ $item->user->userNotes()->count() }})</a>
                                                            @endcan

                                                            @can('access','view_candidate_attachments')
                                                            <a class="dropdown-item" href="{{ route('admin.attachments.index',['user'=>$item->user->id]) }}">@lang('site.attachments') ({{ $item->user->userAttachments()->count() }})</a>
                                                            @endcan



                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
