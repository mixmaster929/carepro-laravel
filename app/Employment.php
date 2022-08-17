<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employment extends Model
{
    protected $fillable = ['employer_id','candidate_id','start_date','end_date','active','salary','salary_type'];


    public function employer(){
        return $this->belongsTo(Employer::class);
    }

    public function candidate(){
        return $this->belongsTo(Candidate::class);
    }

    public function employmentComments(){
        return $this->hasMany(EmploymentComment::class);
    }


}
