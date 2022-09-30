<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    protected $fillable = ['title','description','closes_at','active','location','salary', 'user_id'];

    public function applications(){
        return $this->hasMany(Application::class);
    }

    public function jobCategories(){
        return $this->belongsToMany(JobCategory::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function jobRegions(){
        return $this->belongsToMany(JobRegion::class);
    }

    public function getRegion() {
        return $this->belongsToMany(JobRegion::class)->first();
    }

    public function getCategory(){
        return $this->belongsToMany(JobCategory::class)->first();
    }
}
