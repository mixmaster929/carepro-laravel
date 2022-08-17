<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    protected $fillable = ['name','sort_order'];

    public function vacancies(){
        return $this->belongsToMany(Vacancy::class);
    }
}
