<!-- Modal -->
<div class="modal fade" id="imgUploaderModal" tabindex="-1" role="dialog" aria-labelledby="imgUploaderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="imgUploadForm" action="{{ route('admin.templates.upload') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imgUploaderModalLabel">@lang('site.upload-image')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="@lang('site.close')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="{{ asset('img/loader.gif') }}" id="loaderImg"  class="int_hide"/>
                <h4 id="uploadMsg"></h4>
                <div class="form-group">
                    <label for="image">@lang('site.uploader-text')</label>
                    <input name="image" required class="form-control" type="file"/>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('site.close')</button>
                <button type="submit" class="btn btn-primary">@lang('site.upload')</button>
            </div>
        </div>
        </form>
    </div>
</div>
<script src="{{ asset('vendor/jquery/jquery.form.min.js') }}" type="text/javascript"></script>
<script>
"use strict";
    var thumbId,imgId;

    function image_upload(field, thumb) {
        imgId = field;
        thumbId = thumb;

        $('#imgUploaderModal').modal('show');

    };

    var options = {
        //  target:        '#output2',   // target element(s) to be updated with server response
        //  beforeSubmit:  showRequest,  // pre-submit callback
        success:       showResponse,  // post-submit callback
        dataType:  'json',
        resetForm: true,
        error: getError
        // other available options:
        //url:       url         // override for form's 'action' attribute
        //type:      type        // 'get' or 'post', override for form's 'method' attribute
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type)
        //clearForm: true        // clear all form fields after successful submit
        //resetForm: true        // reset the form after successful submit

        // $.ajax options can be used here too, for example:
        //timeout:   3000
    };

    $('#imgUploadForm').on('submit',function(e){
        e.preventDefault();
        $('#loaderImg').show();
        $(this).ajaxSubmit(options);


    });

    function showResponse(responseText, statusText, xhr, $form){
        $('#loaderImg').hide();
        console.log(responseText);
        if(responseText.status){
            $('#imgUploaderModal').modal('hide');
            $('#'+thumbId).attr('src',responseText.file_path);
            $('#'+imgId).val(responseText.file_name);
        }
        else{
            $('#uploadMsg').text(responseText.error);
        }
    }

    function getError(jqXHR,textStatus,errorThrown){
        $('#loaderImg').hide();
        $('#uploadMsg').text('@lang('site.upload-error')');
    }



</script>
