<?php

namespace App\Http\Controllers\Api\Web;

use App\Traits\ApiResponse;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubscriptionPlanController extends Controller
{
    use ApiResponse;
    
    public function getSubscriptionPlans() {

        $data = Subscription::all();

        if ($data->isEmpty()) {
            return $this->error([], 'Subscription plans not found.', 200);
        }
        
        return $this->success($data,'Subscription plans fetched successfully.', 200);
    }
}
