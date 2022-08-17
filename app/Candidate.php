<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = ['user_id','display_name','date_of_birth','gender','picture','employed','shortlisted','locked','public','video_code','cv_path'];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }

    public function orders(){
        return $this->belongsToMany(Order::class);
    }

    public function interviews(){
        return $this->belongsToMany(Interview::class);
    }

    public function employments(){
        return $this->hasMany(Employment::class);
    }

    public function changeUserFillable(){
        $this->fillable =  ['display_name','date_of_birth','gender','picture','cv_path'];
    }

}
