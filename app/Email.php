<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = ['subject','message','cc','sent','send_date','user_id','sender_id','recipient_email','profile_type'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function sender(){
        return $this->belongsTo(User::class);
    }

    public function emailAttachments(){
        return $this->hasMany(EmailAttachment::class);
    }

    public function candidates(){
        return $this->belongsToMany(Candidate::class);
    }

    public function emailResources(){
        return $this->belongsToMany(EmailResource::class);
    }

    public function invoices(){
        return $this->belongsToMany(Invoice::class);
    }
}
