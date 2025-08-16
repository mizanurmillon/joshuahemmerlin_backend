<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'id' => 'integer',
    ];

    public function role_permission()
    {
        return $this->hasMany(RolePermission::class, 'role_id');
    }
}
