<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmploymentComment extends Model
{
    protected $fillable = ['employment_id','user_id','content'];

    protected $touches = ['employment'];

    public function employment(){
        return $this->belongsTo(Employment::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function employmentCommentAttachments(){
        return $this->hasMany(EmploymentCommentAttachment::class);
    }

}
