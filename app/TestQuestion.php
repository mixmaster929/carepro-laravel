<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model
{
    protected $fillable = ['test_id','question','sort_order'];

    public function test(){
        return $this->belongsTo(Test::class);
    }

    public function testOptions(){
        return $this->hasMany(TestOption::class);
    }

    public function setQuestion($value){
        $this->attributes['question'] = saveInlineImages($value);
    }
}
