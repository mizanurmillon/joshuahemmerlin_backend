<?php

namespace App\Http\Controllers\Api\Web;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Membership;

class MembershipController extends Controller
{
    use ApiResponse;
    public function getMemberships() {
       
        $user = auth()->user();
        
        if(!$user) {
            return $this->error([], 'User not found.', 200);
        }
        
        $data = Membership::with('subscription:id,name')->where('user_id', $user->id)->get();

        if($data->isEmpty()) {
            return $this->error([], 'Memberships not found.', 200);
        }
        return $this->success($data,'Memberships fetched successfully.', 200);
    }
}
