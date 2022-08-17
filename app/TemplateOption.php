<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplateOption extends Model
{
    protected $fillable = ['template_id','name','value','enabled'];

    public function template(){
        return $this->belongsTo(Template::class);
    }

}
