<div class="form-group">
    <label for="{{ @$name }}">{{ @$label }}</label>
    <textarea class="form-control rte" name="{{ @$name }}"  >{!! clean( @${@$name} ) !!}</textarea>
</div>
