<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
    protected $fillable = ['name','content','enabled','description'];

    public function users(){
        return $this->belongsToMany(User::class)->withPivot('signed');
    }

}
