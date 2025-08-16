<?php
namespace App\Http\Controllers\Web\Backend\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class StripeSettingController extends Controller
{
    public function index()
    {
        return view('backend.layouts.settings.stripe_settings');
    }

    public function update(Request $request)
    {
        if (User::find(auth()->user()->id)) {
            $request->validate([
                'stripe_key'    => 'required|string',
                'stripe_secret' => 'required|string',
            ]);

            $stripeKey = trim($request->stripe_key);
            $stripeSecret = trim($request->stripe_secret);

            $envContent = File::get(base_path('.env'));
           

            $envContent = preg_replace([
                '/^STRIPE_SECRET=.*$/m',
                '/^STRIPE_PUBLIC=.*$/m',
            ], [
                'STRIPE_PUBLIC='.$stripeKey,
                'STRIPE_SECRET='.$stripeSecret,
            ], $envContent);

            if ($envContent !== null) {
                File::put(base_path('.env'), $envContent);
            }

            return back()->with('t-success', 'Stripe Setting updated successfully.');
        }

        return redirect()->back();
    }


}
