<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRegion extends Model
{
    use HasFactory;

    protected $fillable = ['name','sort_order'];

    public function vacancies(){
        return $this->belongsToMany(Vacancy::class);
    }

    public function orders(){
        return $this->belongsToMany(Order::class);
    }
}
