<div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
    <label for="content" class="control-label">@lang('site.content')</label>
    <textarea class="form-control" rows="5" name="content" type="textarea" id="content" >{{ old('content',isset($ordercomment->content) ? $ordercomment->content : '') }}</textarea>
    {!! clean( $errors->first('content', '<p class="help-block">:message</p>') ) !!}
</div>

@if($formMode=='create')
    <div class="row">
        <div class="col-md-6">
            <div class="form-check">
                <input class="form-check-input" checked  type="checkbox" value="1" id="notify" name="notify">
                <label class="form-check-label" for="notify">
                    @lang('site.notify-employer')
                </label>
            </div>
        </div>
    </div>
@endif

@if($formMode=='edit' && $ordercomment->orderCommentAttachments()->count() > 0)
    <div  class="int_tpmb">
        <p  >
            <span><i class="fa fa-paperclip"></i> {{ $ordercomment->orderCommentAttachments()->count() }} @if($ordercomment->orderCommentAttachments()->count()>1) @lang('site.attachments') @else @lang('site.attachment') @endif - </span>
            <a href="{{ route('admin.order-comments.download-attachments',['orderComment'=>$ordercomment->id]) }}" class="btn btn-default btn-xs">@lang('site.download-all') <i class="fa fa-file-zip-o"></i> </a>
        </p>

        <div>
            <div class="row" >
                @foreach($ordercomment->orderCommentAttachments as $attachment)
                      <div    class="col-md-4 int_mth"  >
                            <a href="{{ route('admin.order-comments.download-attachment',['orderCommentAttachment'=>$attachment->id]) }}">

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
                          <a  onclick="return confirm(&quot;@lang('site.confirm-delete')&quot;)"  class="int_mt10 btn btn-danger btn-block btn-sm" href="{{ route('admin.order-comments.delete-attachment',['orderCommentAttachment'=>$attachment->id]) }}"><i class="fa fa-trash"></i> @lang('site.delete')</a>
                        </div>

                @endforeach


            </div>

        </div>
    </div>
@endif
<div id="dropzone" class="dropmail int_tpmb" >

    <div class="dropzone dropzone-custom needsclick" id="my-dropzone">
        <div class="dz-message needsclick download-custom">
            <i class="fa fa-download" aria-hidden="true"></i>
            <h1>@lang('site.attachments')</h1>
            <h2>@lang('site.upload-info')</h2>

        </div>
    </div>


</div>
<input type="hidden" name="msg_id" value="{{ $msgId }}"/>

<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? __('site.update') : __('site.create') }}">
</div>
