<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    protected $fillable = ['message','comment','send_date','sent','sms_gateway_id'];

    public function smsRecipients(){
        return $this->hasMany(SmsRecipient::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }

    public function smsGateway(){
        return $this->belongsTo(SmsGateway::class);
    }
}
