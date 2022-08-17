<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplateColor extends Model
{
    protected $fillable = ['template_id','original_color','user_color'];

    public function template(){
        return $this->belongsTo(Template::class);
    }
}
