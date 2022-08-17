@if(setting('captcha_type')=='google')
    <script src="https://www.google.com/recaptcha/api.js?render={{ setting('captcha_recaptcha_key') }}"></script>
    <script>

        $('#register-form').on('submit',function(e){
            e.preventDefault();
            $("#submit-button").prop("disabled",true);
            $("#submit-button").text('{{ addslashes(__('site.loading')) }}');
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ setting('captcha_recaptcha_key') }}', {action: 'homepage'}).then(function(token) {
                    $('#register-form').off("submit");
                    jQuery('.captcha_token').val(token);
                    $('#register-form').submit();
                });
            });
        });
    </script>
@endif
