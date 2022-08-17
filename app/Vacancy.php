<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    protected $fillable = ['title','description','closes_at','active','location','salary'];

    public function applications(){
        return $this->hasMany(Application::class);
    }

    public function jobCategories(){
        return $this->belongsToMany(JobCategory::class);
    }
}
