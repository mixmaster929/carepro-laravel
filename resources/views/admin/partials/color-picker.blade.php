<?php
/**
 * Supply the following arguments to this file:
 * label
 * name
 * value
 */
?>
<div class="form-group">
    @if(isset($label))
    <label>{{ $label }}</label>
    @endif

    <div class="input-group myColorPicker">

        <input type="text" class="form-control colorpicker-full"  name="{{ @$name }}" value="{{ @${@$name} }}">

    </div>
</div>