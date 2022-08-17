<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    protected $fillable = ['name','sort_order'];

    public function permissions(){
        return $this->hasMany(Permission::class);
    }
}
