<?php

namespace Eachdemo\Rbac\Models;

use Illuminate\Database\Eloquent\Model;

class RbacPermission extends Model
{
    protected $fillable = [
        'id', 'menu_id', 'name','action',
    ];

    protected $hidden = [
        
    ];

}
