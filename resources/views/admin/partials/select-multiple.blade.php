<div class="form-group">
    <label for="{{ @$name }}">{{ @$label }}</label>
    {{ Form::select(@$name.'[]', $options,@${@$name},['class'=>'form-control select2 '.@$class,'multiple'=>'multiple']) }}
</div>
