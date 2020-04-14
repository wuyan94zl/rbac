<?php

namespace Eachdemo\Rbac\Models;

use Illuminate\Database\Eloquent\Model;

class RbacAdminHasRole extends Model
{
    protected $fillable = [
        'admin_id', 'role_id'
    ];

    protected $hidden = [
        
    ];

}
