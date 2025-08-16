<?php
namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleAccess;
use App\Models\RolePermission;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolePermissionController extends Controller
{
    use ApiResponse;

    public function getRole()
    {

        $data = Role::all();

        if ($data->isEmpty()) {
            return $this->error([], 'No roles found', 404);
        }

        return $this->success($data, 'Roles found successfully', 200);

    }

    public function getPermission()
    {

        $data = Permission::all();

        if ($data->isEmpty()) {
            return $this->error([], 'No permissions found', 404);
        }

        return $this->success($data, 'Permissions found successfully', 200);
    }

    public function rolePermission(Request $request)
    {
        $user = auth()->user();

        if (! $user) {
            return $this->error([], 'User not found', 404);
        }

        $validateData = Validator::make($request->all(), [
            'role_id'       => 'required|integer',
            'permission_id' => 'required|integer',
            'is_permission' => 'required|boolean',
        ]);

        if ($validateData->fails()) {
            return $this->error($validateData->errors(), 422);
        }

        if ($request->permission_id == 1) {
            //store all permissions
            $allData     = [];
            $permissions = Permission::all();

            //insert all permissions
            foreach ($permissions as $permission) {
                $data = RolePermission::updateOrCreate(
                    [
                        'role_id'       => $request->role_id,
                        'permission_id' => $permission->id,
                    ],
                    [
                        'user_id'       => $user->id,
                        'is_permission' => $request->is_permission,
                    ]
                );

                $allData[] = $data;
            }

            return $this->success($allData, 'Role permission saved successfully', 200);
        }

        $data = RolePermission::where('role_id', $request->role_id)
            ->where('permission_id', $request->permission_id)
            ->first();

        if (! $data) {
            $data                = new RolePermission();
            $data->user_id       = $user->id;
            $data->role_id       = $request->role_id;
            $data->permission_id = $request->permission_id;
        }

        $data->is_permission = $request->is_permission;
        $data->save();

        return $this->success($data, 'Role permission saved successfully', 200);
    }

    public function getRolePermission()
    {
        $user = auth()->user();

        if (! $user) {
            return $this->error([], 'User not found', 404);
        }

        $data = RolePermission::where('user_id', $user->id)->get();

        if ($data->isEmpty()) {
            return $this->error([], 'No role permissions found', 404);
        }

        return $this->success($data, 'Role permissions found successfully', 200);
    }

    public function roleAssign(Request $request, $id)
    {
        $user = auth()->user();

        if (! $user) {
            return $this->error([], 'User not found', 404);
        }

        $userdata = User::find($id);

        // dd($userdata->userRole->role_id);

        $rolePermission = RolePermission::where('role_id', $userdata->userRole->role_id)->where('is_permission', true)->first();

        if (! $rolePermission) {
            return $this->error([], 'You do not have permission to access', 404);
        }

        $data = RoleAccess::where('user_id', $id)->first();

        if (!$data) {
            $data          = new RoleAccess();
            $data->user_id = $id;
            $data->role_id = $userdata->userRole->role_id;
            $data->access  = $request->access;
        } else {
            $data->access = $request->access;
        }

        $data->save();

        return $this->success($data, 'Role access saved successfully', 200);

    }

}
