@extends('layouts.admin-page')

@section('pageTitle',__('site.employment-comment'))
 @section('page-title',$employmentcomment->employment->employer->user->name.'('.__('site.employer').') - '.$employmentcomment->employment->candidate->user->name.'('.__('site.candidate').')')
@section('page-content')
    <div class="container-fluid">
        <div class="row">


            <div class="col-md-12">
                <div  >
                    <div  >
                        @can('access','view_employment_comments')
                        <a href="{{ route('admin.employment-comments.index',['employment'=>$employmentcomment->employment_id]) }}"  ><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> @lang('site.back')</button></a>
                        @endcan

                        @can('access','edit_employment_comment')
                        <a href="{{ url('/admin/employment-comments/' . $employmentcomment->id . '/edit') }}" ><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> @lang('site.edit')</button></a>
                        @endcan

                        @can('access','delete_employment_comment')
                        <form method="POST" action="{{ url('admin/employment-comments' . '/' . $employmentcomment->id) }}" accept-charset="UTF-8" class="int_inlinedisp">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-sm" title="@lang('site.delete')" onclick="return confirm(&quot;@lang('site.confirm-delete')?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> @lang('site.delete')</button>
                        </form>
                        @endcan

                        <br/>
                        <br/>

                        <div >
                            <table class="table">
                                <tbody>
                                <tr><th> @lang('site.created-by') </th><td>
                                        {{ $employmentcomment->user->name }} ({{ roleName($employmentcomment->user->role_id) }})
                                    </td></tr>
                                    <tr><th> @lang('site.added-on') </th><td>

                                            {{ \Illuminate\Support\Carbon::parse($employmentcomment->created_at)->format('d/M/Y') }}
                                        </td></tr>
                                </tbody>
                            </table>

                            <div class="card int_twmb20" >
                                <div class="card-body">
                                    {!! clean( check($employmentcomment->content) ) !!}
                                </div>

                            </div>

                            @if( $employmentcomment->employmentCommentAttachments()->count() > 0)
                                <div  class="int_tpmb">
                                    <p  >
                                        <span><i class="fa fa-paperclip"></i> {{ $employmentcomment->employmentCommentAttachments()->count() }} @if($employmentcomment->employmentCommentAttachments()->count()>1) @lang('site.attachments') @else @lang('site.attachment') @endif - </span>
                                        <a href="{{ route('admin.employment-comments.download-attachments',['employmentComment'=>$employmentcomment->id]) }}" class="btn btn-default btn-xs">@lang('site.download-all') <i class="fa fa-file-zip-o"></i> </a>
                                    </p>

                                    <div>
                                        <div class="row" >
                                            @foreach($employmentcomment->employmentCommentAttachments as $attachment)
                                                <div class="col-md-4 int_mbh"   >
                                                    <a href="{{ route('admin.employment-comments.download-attachment',['employmentCommentAttachment'=>$attachment->id]) }}">

                                                        <div class="card"  >


                                                            @if(isImage($attachment->file_path))
                                                                <img  src="{{ route('admin.image') }}?file={{ $attachment->file_path }}"  class="card-img-top int_mh270" alt=""/>
                                                            @endif

                                                            <div class="card-body int_txcen"   >
                                                                @if(!isImage($attachment->file_path))
                                                                    <i class="int_fs200 fa fa-file text-info"></i>
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
        </div>
    </div>
@endsection
