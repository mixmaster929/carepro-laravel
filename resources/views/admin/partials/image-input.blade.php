<?php
//when including this file, supply two variables: name and label required
$id = uniqid();
$thumbId = 'thumb_'.$id;
$imgId = 'img_'.$id;
?>
<div class="form-group">
    <label for="file">{{ @$label }}</label> <br/>

    <div class="image-box">
        <div>
            @if(!empty(@${@$name}) && file_exists(@${@$name}))
                <img src="{{ asset(@${@$name}) }}" id="{{ $thumbId }}" />
            @else
                <img src="{{ asset('img/no_image.jpg') }}" id="{{ $thumbId }}"/>
            @endif
            <input @if(isset($required) && $required) required @endif id="{{ $imgId }}" type="hidden" name="{{ @$name }}" value="{{ @${@$name} }}"/>
        </div>
        <a class="pointer" onclick="image_upload('{{ $imgId }}', '{{ $thumbId }}');">@lang('site.browse')</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="pointer" onclick="$('#{{ $thumbId }}').attr('src', '{{ asset('img/no_image.jpg') }}'); $('#{{ $imgId }}').attr('value', '');">@lang('site.clear')</a>

    </div>
</div>
