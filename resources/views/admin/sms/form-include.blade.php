@section('header')
    <link href="{{ asset('vendor/pickadate/themes/default.date.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/pickadate/themes/default.time.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/pickadate/themes/default.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/jquery-toast-plugin/dist/jquery.toast.min.css') }}">
@endsection


@section('footer')
    <script src="{{ asset('vendor/pickadate/picker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/picker.date.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/picker.time.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/pickadate/legacy.js') }}" type="text/javascript"></script>
    <script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>
    <script>
"use strict";

        $('#template').on('change',function(){
            console.log('loading...');
            $.toast('@lang('site.fetching-template')')
            var id = $(this).val();
            $.get('{{ url('/admin/get-sms-template') }}/'+id,function(data){
                console.log(data.message);
                //$("#message").summernote("code", data.message);
                $('#message').val(data);

                $('html, body').animate({
                    scrollTop: ($('#message').offset().top)
                },500);
            });
        });






        $('#users').select2({
            placeholder: "{{ addslashes(__('site.search-users')) }}",
            minimumInputLength: 3,
            ajax: {
                url: '{{ route('admin.users.search') }}?format=number',
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




        $(document).ready(function(){
            var $remaining = $('#remaining'),
                    $messages = $remaining.next();

            $('#message').on('keyup',function(){
                var chars = this.value.length,
                    messages = Math.ceil(chars / 160),
                    remaining = messages * 160 - (chars % (messages * 160) || messages * 160);

                $remaining.text(remaining + ' @lang('site.characters-remaining').');
                $messages.text(messages + ' @lang('site.message_s')');
            });

            $('#message').trigger('keyup');
        });



    </script>

    <script src="{{ asset('js/admin/smsforminclude.js') }}"></script>
@endsection
