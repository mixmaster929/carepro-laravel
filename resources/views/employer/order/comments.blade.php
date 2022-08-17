<div class="az-content-body az-content-body-chat">

    <div id="azChatBody" class="az-chat-body">
        <div class="content-inner">
            @foreach($comments as $comment)
            <div class="media @if($comment->user_id != $comment->order->user_id) flex-row-reverse @endif">
                <div class="az-img-user "><img src="{{ userPic($comment->user_id) }}" alt="">

                </div>
                <div class="media-body">
                    <div class="az-msg-wrapper">
                        {!! clean( nl2br(clean($comment->content)) ) !!}
                    </div><!-- az-msg-wrapper -->

                    @if($comment->orderCommentAttachments()->count()>0)
                        <p  >
                            <span><i class="fa fa-paperclip"></i> {{ $comment->orderCommentAttachments()->count() }} @if($comment->orderCommentAttachments()->count()>1) @lang('site.attachments') @else @lang('site.attachment') @endif - </span>
                            <a href="{{ route('employer.order-comments.download-attachments',['orderComment'=>$comment->id]) }}" class="btn btn-default btn-xs">@lang('site.download-all') <i class="fa fa-file-zip-o"></i> </a>
                        </p>

                        <div class="row int_mxw300"  >

                        @foreach($comment->orderCommentAttachments as $attachment)
                            <div class="col-md-12 int_tpmb"   >
                                <a href="{{ route('employer.order-comments.download-attachment',['orderCommentAttachment'=>$attachment->id]) }}">

                                    <div class="card"  >


                                        @if(isImage($attachment->file_path))
                                            <img   src="{{ route('employer.image') }}?file={{ $attachment->file_path }}"  class="int_mh270 card-img-top" alt=""/>
                                        @endif

                                        <div class="card-body int_txcen"   >
                                            @if(!isImage($attachment->file_path))
                                                <i   class="int_fs200 fa fa-file text-info"></i>
                                            @endif
                                        </div>
                                        <div class="card-footer">
                                            {{ $attachment->file_name }}
                                        </div>


                                    </div>
                                </a>
                            </div>

                        @endforeach
                    </div><!-- az-msg-wrapper -->
                    @endif


                    <div><span>@lang('site.by') {{ $comment->user->name }} ({{ roleName($comment->user->role_id) }}) @lang('site.on') {{ $comment->created_at->format('d/M/Y') }}</span> <a href="#"><i class="icon ion-android-more-horizontal"></i></a></div>
                </div><!-- media-body -->
            </div><!-- media -->
            @endforeach




        </div><!-- content-inner -->
    </div><!-- az-chat-body -->
    <div class="comment-links">
        {{ $comments->links() }}
    </div>

</div><!-- az-content-body -->
