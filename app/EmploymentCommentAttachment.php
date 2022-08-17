<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmploymentCommentAttachment extends Model
{
    protected $fillable = ['file_name','file_path','employment_comment_id'];

    public function employmentComment(){
        return $this->belongsTo(EmploymentComment::class);
    }


}
