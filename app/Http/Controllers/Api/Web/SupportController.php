<?php

namespace App\Http\Controllers\Api\Web;

use App\Models\SupportForm;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupportController extends Controller
{
    use ApiResponse;
    public function supportForm(Request $request) {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:50000',
        ]);

        $supportForm = new SupportForm();
        $supportForm->name = $request->name;
        $supportForm->email = $request->email;
        $supportForm->subject = $request->subject;
        $supportForm->message = $request->message;
        $supportForm->save();

        if (!$supportForm) {
            return $this->error([], 'Support form not submitted.', 400);
        }

        return $this->success($supportForm, 'Support form submitted successfully.', 200);
    }
}
