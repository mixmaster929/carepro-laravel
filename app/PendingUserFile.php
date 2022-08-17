<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PendingUserFile extends Model
{
    protected $fillable=['file_name','file_path','pending_user_id','field_id'];

    public function pendingUser(){
        return $this->belongsTo(PendingUser::class);
    }

}
