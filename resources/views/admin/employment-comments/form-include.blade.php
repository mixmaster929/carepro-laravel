
@section('footer')
    <script src="{{ asset('vendor/summernote/summernote-bs4.min.js') }}"></script>

    <!-- dropzone JS
		============================================ -->
    <script src="{{ asset('vendor/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('js/admin/ofg-edit.js') }}"></script>
    <script>
"use strict";

        Dropzone.autoDiscover = false;
        jQuery(document).ready(function() {

            $("div#my-dropzone").dropzone({
                url: "{{ route('admin.employment-comments.upload',['id'=>$msgId]) }}",
                acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf,.doc,.docx,.ppt,.pptx,.zip,.mp3,.mp4",
                maxFilesize: 20, // MB
                success: function (file, response) {
                    console.log("Sucesso");
                    console.log(response);
                },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                addRemoveLinks: true,
                removedfile: function(file) {
                    var name = file.name;
                    console.log(name);

                    $.ajax({
                        type: 'POST',
                        url: '{{ route('admin.employment-comments.remove-upload',['id'=>$msgId]) }}',
                        data: {name: name,request: 2, _token:'{{ csrf_token() }}'},
                        sucess: function(data){
                            console.log('success: ' + data);
                        }
                    });
                    var _ref;
                    return (_ref = file.previewElement) != null? _ref.parentNode.removeChild(file.previewElement) : void 0;
                },
                dictRemoveFile: '@lang('site.remove-file')',
                dictCancelUpload: '@lang('site.cancel-upload')',
                dictCancelUploadConfirmation: '@lang('site.cancel-confirm')'
            });

        });

    </script>
@endsection


@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/dropzone/dropzone.css') }}">

@endsection
