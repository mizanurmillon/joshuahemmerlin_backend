<?php

namespace App\Http\Controllers\Api\Web;

use App\Models\UploadLogo;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UploadLogoController extends Controller
{
    use ApiResponse;

    public function uploadLogo(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validateData->fails()) {
            return $this->error($validateData->errors(), 422);
        }

        $user = auth()->user();

        $existingLogo = UploadLogo::where('user_id', $user->id)->first();
        if ($request->hasFile('logo')) {

            if ($user->logo) {
                $previousImagePath = public_path($user->logo);
                if (file_exists($previousImagePath)) {
                    unlink($previousImagePath);
                }
            }

            $image     = $request->file('logo');
            $imageName = uploadImage($image, 'logo');
        }

        if ($existingLogo) {
            $data = UploadLogo::find($existingLogo->id);
            $data->update([
                'logo' => $imageName,
            ]);
        }else{
            $data = UploadLogo::create([
                'user_id' => $user->id,
                'logo' => $imageName,
            ]);
        }

        return $this->success($data, 'Logo uploaded successfully', 200);
    }
}
