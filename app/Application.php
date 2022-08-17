<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = ['user_id','vacancy_id','shortlisted'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function vacancy(){
        return $this->belongsTo(Vacancy::class);
    }

}
