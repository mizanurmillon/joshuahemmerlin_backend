<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $guarded = [];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'id'            => 'integer',
        'role_id'       => 'integer',
        'permission_id' => 'integer',
        'is_permission' => 'boolean',
    ];
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
