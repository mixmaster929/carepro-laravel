@section('header')
    <link href="{{ asset('vendor/pickadate/themes/default.date.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/pickadate/themes/default.time.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/pickadate/themes/default.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/dropzone/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/jquery-toast-plugin/dist/jquery.toast.min.css') }}">
@endsection


@section('footer')
    <script src="{{ asset('vendor/pickadate/picker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/picker.date.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/picker.time.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/legacy.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('vendor/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('vendor/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('vendor/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>
    <script src="{{ asset('js/admin/email-form.js') }}"></script>


    <script>
"use strict";
$('#template').on('change',function(){
    console.log('loading...');
    $.toast('@lang('site.fetching-template')');
    var id = $(this).val();
    $.get('{{ url('/admin/get-email-template') }}/'+id,function(data){
        console.log(data.message);
        $('#subject').val(data.subject);
        $("#message").summernote("code", data.message);

        $('html, body').animate({
            scrollTop: ($('#subject').offset().top)
        },500);
    });
});





        $('#candidates').select2({
            placeholder: "@lang('site.search-candidates')...",
            minimumInputLength: 3,
            ajax: {
                url: '{{ route('admin.candidates.search') }}?format=candidate_id',
                dataType: 'json',
                data: function (params) {
                    return {
                        term: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }

        });


        $('#user_id').select2({
            placeholder: "@lang('site.search-users')...",
            minimumInputLength: 3,
            ajax: {
                url: '{{ route('admin.users.search') }}',
                dataType: 'json',
                data: function (params) {
                    return {
                        term: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }

        });




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
