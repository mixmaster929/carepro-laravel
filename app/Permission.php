<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name','permission_group_id'];

    public function permissionGroup(){
        return $this->belongsTo(PermissionGroup::class);
    }

}
