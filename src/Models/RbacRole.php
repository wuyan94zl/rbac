<?php

namespace Eachdemo\Rbac\Models;

use Illuminate\Database\Eloquent\Model;

class RbacRole extends Model
{
    protected $fillable = [
        'id', 'name', 'remark'
    ];

    protected $hidden = [
        
    ];
}
