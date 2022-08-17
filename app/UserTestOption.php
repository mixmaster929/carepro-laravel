<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTestOption extends Model
{
    protected $fillable = ['user_test_id','test_option_id'];

    public function userTest(){
        return $this->belongsTo(UserTest::class);
    }

    public function testOption(){
        return $this->belongsTo(TestOption::class);
    }

}
