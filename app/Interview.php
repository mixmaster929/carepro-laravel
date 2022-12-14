<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = ['application_id', 'user_id','interview_date','interview_time','venue','internal_note','employer_comment','reminder','feedback','hash','employer_feedback'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function candidates(){
        return $this->belongsToMany(Candidate::class);
    }

    public function application(){
        return $this->belongsTo(Application::class);
    }

}
