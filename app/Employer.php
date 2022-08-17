<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    protected $fillable = ['user_id','active','gender'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function employments(){
        return $this->hasMany(Employment::class);
    }

}
