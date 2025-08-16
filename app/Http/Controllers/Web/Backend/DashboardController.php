<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SupportForm;

class DashboardController extends Controller
{
    public function index() {
        $total_owners = User::where('role','admin')->where('role', '!=', 'superadmin')->count();
        $total_users = User::where('role','user')->count();
        $total_support = SupportForm::count();
        return view('backend.layouts.index', compact('total_users', 'total_owners', 'total_support'));
    }
}
