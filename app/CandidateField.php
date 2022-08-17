<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CandidateField extends Model
{
    protected $fillable = ['candidate_field_group_id','name','type','sort_order','options','required','placeholder','enabled','filter'];

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function candidateFieldGroup(){
        return $this->belongsTo(CandidateFieldGroup::class);
    }

}
