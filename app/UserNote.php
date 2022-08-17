<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserNote extends Model
{
    protected $fillable = ['user_id','title','content','author'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
