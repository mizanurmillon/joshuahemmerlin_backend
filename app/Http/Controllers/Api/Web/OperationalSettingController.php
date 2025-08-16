<?php

namespace App\Http\Controllers\Api\Web;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\OperationalSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OperationalSettingController extends Controller
{
    use ApiResponse;

    public function operationalSettingsUpdate(Request $request) {
        $validateData = Validator::make($request->all(), [
            'time_zone' => 'required|string',
        ]);

        if ($validateData->fails()) {
            return $this->error($validateData->errors(), 422);
        }

        $user = auth()->user();
       
        $existingLogo = OperationalSetting::where('user_id', $user->id)->first();
        if ($existingLogo) {
            $data = OperationalSetting::find($existingLogo->id);
            $data->update([
                'time_zone' => $request->time_zone,
            ]);
            return $this->success($data,'Time zone updated successfully', 200);
        }else{
            $data = OperationalSetting::create([
                'user_id' => $user->id,
                'time_zone' => $request->time_zone,
            ]);
            return $this->success($data,'Time zone set successfully', 200);
        }
    }

    public function operationalSettings()
    {

        $user = auth()->user();

        $data = OperationalSetting::where('user_id', $user->id)->first();
        
        if ($data) {
            return $this->success($data,'Time zone found successfully', 200);
        }else{
            return $this->success($data,'No time zone found', 200);
        }
    }
}
