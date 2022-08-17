<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = ['name','enabled','directory'];

    public function templateOptions(){
        return $this->hasMany(TemplateOption::class);
    }

    public function templateColors(){
        return $this->hasMany(TemplateColor::class);
    }



}
