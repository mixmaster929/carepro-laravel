<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PendingUser extends Model
{
    protected $fillable = ['role_id','data','hash'];

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function pendingUserFiles(){
        return $this->hasMany(PendingUserFile::class);
    }
}
