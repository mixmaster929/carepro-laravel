@extends('layouts.admin-page')

@section('pageTitle',__('site.view-email'))

@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >
                        @can('access','view_emails')
                        <a href="{{ url('/admin/emails') }}" title="@lang('site.back')"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        @endcan

                        @can('access','edit_email')
                            @if($email->sent==0)
                                <a href="{{ url('/admin/emails/' . $email->id . '/edit') }}"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>
                            @endif
                        @endcan

                        @can('access','delete_email')
                        <form method="POST" action="{{ url('admin/emails' . '/' . $email->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
                        @endcan
                        <br/>
                        <br/>


                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h2>{{ $email->subject }}</h2>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="float-right">{{ \Illuminate\Support\Carbon::parse($email->send_date)->diffForHumans() }}</span>
                                    </div>
                                </div>


                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4>@lang('site.to'): <a href="{{ userLink($email->user) }}">{{ $email->user->name }}</a> ({{ roleName($email->user->role_id) }})</h4>
                                        @if($email->sender()->exists())
                                            <h4>@lang('site.created-by'): {{ $email->sender->name }} ({{ $email->sender->email }})</h4>
                                            @endif
                                        @if(!empty($email->cc))
                                            <h4>CC: {{ $email->cc }}</h4>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <h5>@lang('site.created-on'): {{ \Illuminate\Support\Carbon::parse($email->created_at)->format('d/M/Y') }}</h5>
                                        <h5>@lang('site.send-date'): {{ \Illuminate\Support\Carbon::parse($email->send_date)->format('d/M/Y') }}</h5>

                                    </div>
                                </div>



                                {!! clean( check($email->message) ) !!}
                            </div>
                        </div>

                        @if($email->emailAttachments()->exists() || $email->invoices()->exists() || $email->emailResources()->exists() || $email->candidates()->exists() )

                        <br/>
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title">
                                    <i class="fa fa-paperclip"></i>    @lang('site.attachments')
                                </div>

                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs">

                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#home">@lang('site.resumes') ({{ $email->candidates()->count() }})</a>
                                    </li>


                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#menu1">@lang('site.email-resources') ({{ $email->emailResources()->count() }})</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#menu2">@lang('site.invoices') ({{ $email->invoices()->count() }})</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#menu3">@lang('site.files') ({{ $email->emailAttachments()->count() }})</a>
                                    </li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content">

                                    <div class="tab-pane container active" id="home">
                                        <br/>
                                        <div class="table-responsive" >
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>@lang('site.picture')</th>
                                                    <th>@lang('site.candidate')</th>
                                                    <th>@lang('site.gender')</th>
                                                    <th>@lang('site.email')</th>
                                                    <th>@lang('site.age')</th>
                                                    <th>@lang('site.profile-type')</th>
                                                    <th>@lang('site.actions')</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($email->candidates as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td><div class="media align-items-center">
              <span class="avatar avatar-sm rounded-circle">
                  @if(!empty($item->picture) && file_exists($item->picture))
                      <img   src="{{ asset($item->picture) }}">
                  @else
                      <img   src="{{ asset('img/man.jpg') }}">
                  @endif

              </span>
                                                            </div></td>
                                                        <td>{{ $item->user->name }}</td>
                                                        <td>{{ gender($item->gender) }}</td>
                                                        <td>{{ $item->user->email }}</td>
                                                        <td>{{  getAge(\Illuminate\Support\Carbon::parse($item->date_of_birth)->timestamp) }}
                                                        </td>
                                                        <td>
                                                            @if($email->profile_type=='p')
                                                                @lang('site.partial')
                                                            @else
                                                                @lang('site.full')
                                                            @endif
                                                        </td>

                                                        <td>

                                                            @can('access','view_candidate')
                                                            <div class="btn-group dropup">
                                                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="ni ni-settings"></i> @lang('site.actions')
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <!-- Dropdown menu links -->
                                                                    <a target="_blank" class="dropdown-item" href="{{ route('admin.candidates.show',['candidate'=>$item->user_id]) }}"><i class="fa fa-eye"></i> @lang('site.view')</a>

                                                                    @if($email->profile_type=='p')
                                                                        <a class="dropdown-item" href="{{ route('admin.candidate.download',['id'=>$item->user_id,'full'=>0]) }}"><i class="fa fa-download"></i> @lang('site.download-resume')</a>
                                                                    @else
                                                                        <a class="dropdown-item" href="{{ route('admin.candidate.download',['id'=>$item->user_id,'full'=>1]) }}"><i class="fa fa-download"></i> @lang('site.download-resume')</a>

                                                                    @endif

                                                                </div>
                                                            </div>







                                                            @endcan

                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>

                                    <div class="tab-pane container fade" id="menu1">
                                        <br/>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>#</th><th>@lang('site.name')</th><th>@lang('site.file')</th><th>@lang('site.actions')</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($email->emailResources as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration  }}</td>
                                                        <td>{{ $item->name }}</td><td>{{ $item->file_name }}</td>
                                                        <td>

                                                            @can('access','view_email_resource')
                                                            @if(isImage($item->file_path))
                                                                <a href="#"  data-toggle="modal" data-target="#pictureModal{{ $item->id }}"  title="@lang('site.view')"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> @lang('site.view')</button></a>
                                                                <div class="modal fade" id="pictureModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="pictureModal{{ $item->id }}Label" aria-hidden="true">
                                                                    <div class="modal-dialog modal-lg" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="pictureModal{{ $item->id }}Label">@lang('site.picture')</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body int_txcen">
                                                                                <img src="{{ route('admin.image') }}?file={{ $item->file_path }}" class="int_wm100pc" />
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('site.close')</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>




                                                            @endif
                                                            <a href="{{ url('/admin/email-resources/' . $item->id ) }}" title="@lang('site.download')"><button class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i> @lang('site.download')</button></a>
                                                            @endcan


                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>



                                    </div>
                                    <div class="tab-pane container fade" id="menu2">
                                        <br/>
                                        <div class="table-responsive" >
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>@lang('site.item')</th>
                                                    <th>@lang('site.amount')</th>
                                                    <th>@lang('site.status')</th>
                                                    <th>@lang('site.created-on')</th>
                                                    <th>@lang('site.actions')</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($email->invoices()->latest()->get() as $item)
                                                    <tr>
                                                        <td>{{ $item->id }}</td>
                                                        <td>{{ $item->title }} </td>
                                                        <td>{!! clean( check( price($item->amount)) ) !!}</td>
                                                        <td>{{ ($item->paid==1)? __('site.paid'):__('site.unpaid') }}</td>
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d/M/Y') }}
                                                        </td>
                                                        <td>
                                                            @can('access','approve_invoice')
                                                            @if($item->paid==0)
                                                                <a onclick="return confirm('@lang('site.confirm-approve')')" class="btn btn-success btn-sm" href="{{ route('admin.invoices.approve',['invoice'=>$item->id]) }}"><i class="fa fa-thumbs-up"></i> @lang('site.approve')</a>
                                                            @endif
                                                            @endcan

                                                            @can('access','view_invoice')
                                                            <a target="_blank" href="{{ url('/admin/invoices/' . $item->id) }}" title="@lang('site.view')"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> @lang('site.view')</button></a>
                                                            @endcan
                                                            &nbsp;

                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>



                                    </div>
                                    <div class="tab-pane container fade" id="menu3">
                                        <br/>
                                        @if( $email->emailAttachments()->count() > 0)
                                            <div class="int_tpmb">
                                                <p  >
                                                    <span><i class="fa fa-paperclip"></i> {{ $email->emailAttachments()->count() }} @if($email->emailAttachments()->count()>1) @lang('site.attachments') @else @lang('site.attachment') @endif - </span>
                                                    <a href="{{ route('admin.emails.download-attachments',['email'=>$email->id]) }}" class="btn btn-default btn-xs">@lang('site.download-all') <i class="fa fa-file-zip-o"></i> </a>
                                                </p>

                                                <div>
                                                    <div class="row" >
                                                        @foreach($email->emailAttachments as $attachment)
                                                            <div class="col-md-4 int_mbh20395"   >
                                                                <a href="{{ route('admin.emails.download-attachment',['emailAttachment'=>$attachment->id]) }}">

                                                                    <div class="card"  >


                                                                        @if(isImage($attachment->file_path))
                                                                            <img   src="{{ route('admin.image') }}?file={{ $attachment->file_path }}"  class="card-img-top int_mh270"  alt=""/>
                                                                        @endif

                                                                        <div class="card-body int_txcen"  >
                                                                            @if(!isImage($attachment->file_path))
                                                                                <i  class="int_fs200 fa fa-file text-info"></i>
                                                                            @endif
                                                                        </div>
                                                                        <div class="card-footer">
                                                                            {{ $attachment->file_name }}
                                                                        </div>


                                                                    </div>
                                                                </a>

                                                            </div>

                                                        @endforeach


                                                    </div>

                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>


                            </div>


                        </div>


                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
