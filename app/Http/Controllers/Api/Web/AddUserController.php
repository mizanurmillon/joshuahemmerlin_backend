<?php
namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Mail\UserMail;
use App\Models\User;
use App\Models\UserRole;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AddUserController extends Controller
{
    use ApiResponse;

    public function addUser(Request $request)
    {

        $validateData = Validator::make($request->all(), [
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string',
            'role_id'  => 'required',
        ]);

        if ($validateData->fails()) {
            return $this->error($validateData->errors(), 422);
        }

        $auth_user = auth()->user();

        try {
            DB::beginTransaction();

            $password      = $request->input('password');
            $password_hash = Hash::make($password);

            $user = User::create([
                'referred_id'       => $auth_user->id,
                'name'              => $request->name,
                'email'             => $request->email,
                'password'          => $password_hash,
                'role'              => 'user',
                'email_verified_at' => now(),
            ]);

            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $request->role_id,
            ]);

            $data = [
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => $password,
                'role_id'  => $request->role_id,
            ];

            DB::commit();
            Mail::to($user->email)->send(new UserMail($data));
            $password = $request->input('password');

            $user->load('userRole');

            return $this->success($user, 'User added successfully', 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), 500);
        }

    }

    public function getUsers()
    {

        $user = auth()->user();

        if (! $user) {
            return $this->error([], 'User not found', 404);
        }

        $data = User::with('userRole.role:id,role_name', 'roleAccess:user_id,access')->where('referred_id', $user->id)->get();

        if ($data->isEmpty()) {
            return $this->error([], 'No users found', 404);
        }

        return $this->success($data, 'Users found successfully', 200);

    }

    public function getUser($id)
    {

        $user = auth()->user();

        if (! $user) {
            return $this->error([], 'User not found', 404);
        }

        $data = User::with('userRole.role:id,role_name', 'roleAccess:user_id,access')->where('referred_id', $user->id)->where('id', $id)->first();

        if (! $data) {
            return $this->error([], 'User not found', 404);
        }

        return $this->success($data, 'User found successfully', 200);

    }

    public function updateUser(Request $request, $id)
    {

        $validateData = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validateData->fails()) {
            return $this->error($validateData->errors(), 422);
        }

        $user = auth()->user();

        if (! $user) {
            return $this->error([], 'User not found', 404);
        }

        $data = User::with('userRole.role:id,role_name', 'roleAccess:user_id,access')->where('referred_id', $user->id)->where('id', $id)->first();

        if (! $data) {
            return $this->error([], 'User not found', 404);
        }

        $data->update([
            'name' => $request->name,
        ]);

        UserRole::where('user_id', $id)->update([
            'role_id' => $request->role_id,
        ]);

        $data->load('userRole');

        return $this->success($data, 'User updated successfully', 200);

    }

    public function deleteUser($id)
    {

        $user = auth()->user();

        if (! $user) {
            return $this->error([], 'User not found', 404);
        }

        $data = User::with('userRole.role:id,role_name', 'roleAccess:user_id,access')->where('referred_id', $user->id)->where('id', $id)->first();

        if (! $data) {
            return $this->error([], 'User not found', 404);
        }

        $data->delete();

        return $this->success([], 'User deleted successfully', 200);

    }

}
