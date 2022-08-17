<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsRecipient extends Model
{
   protected $fillable = ['sms_id','telephone'];

    public function sms(){
        return $this->belongsTo(Sms::class);
    }

}
