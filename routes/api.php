<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\DataSourcesController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\KPIDashboardController;
use App\Http\Controllers\Api\ShopwareController;
use App\Http\Controllers\Api\SitesettingController;
use App\Http\Controllers\Api\SocialAuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Web\AddUserController;
use App\Http\Controllers\Api\Web\DataSourceManagementController;
use App\Http\Controllers\Api\Web\MembershipController;
use App\Http\Controllers\Api\Web\OperationalSettingController;
use App\Http\Controllers\Api\Web\PaymentController;
use App\Http\Controllers\Api\Web\RolePermissionController;
use App\Http\Controllers\Api\Web\SubscriptionPlanController;
use App\Http\Controllers\Api\Web\SupportController;
use App\Http\Controllers\Api\Web\UploadLogoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Social Login
Route::post('/social-login', [SocialAuthController::class, 'socialLogin']);

//Register API
Route::controller(RegisterController::class)->prefix('users/register')->group(function () {
    // User Register
    Route::post('/', 'userRegister');

    // Verify OTP
    Route::post('/otp-verify', 'otpVerify');

    // Resend OTP
    Route::post('/otp-resend', 'otpResend');
});

//Login API
Route::controller(LoginController::class)->prefix('users/login')->group(function () {

    // User Login
    Route::post('/', 'userLogin');

    // Verify Email
    Route::post('/email-verify', 'emailVerify');

    // Resend OTP
    Route::post('/otp-resend', 'otpResend');

    // Verify OTP
    Route::post('/otp-verify', 'otpVerify');

    //Reset Password
    Route::post('/reset-password', 'resetPassword');
});

Route::controller(SitesettingController::class)->group(function () {
    Route::get('/site-settings', 'siteSettings');
});

//FAQ APIs
Route::controller(FaqController::class)->group(function () {
    Route::get('/faq/all', 'FaqAll');
});

Route::group(['middleware' => ['jwt.verify']], function () {

    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('/data', 'userData');
        Route::post('/data/update', 'userUpdate');
        Route::post('/password/change', 'passwordChange');
        Route::post('/logout', 'logoutUser');
        Route::delete('/delete', 'deleteUser');
    });

    Route::group(['middleware' => ['admin']], function () {

        Route::group(['middleware' => ['is_membership']], function () {

            Route::controller(AddUserController::class)->prefix('admin')->group(function () {
                Route::post('/add-user', 'addUser');
                Route::get('/users', 'getUsers');
                Route::get('/user/{id}', 'getUser');
                Route::post('/user-update/{id}', 'updateUser');
                Route::delete('/user-delete/{id}', 'deleteUser');
            });

            Route::controller(RolePermissionController::class)->prefix('admin')->group(function () {
                Route::get('/roles', 'getRole');
                Route::get('/permissions', 'getPermission');

                Route::post('/role-permission', 'rolePermission');
                Route::get('/role-permissions', 'getRolePermission');
                Route::post('/role-assign/{id}', 'roleAssign');
            });

        });

        Route::controller(MembershipController::class)->prefix('admin')->group(function () {
            Route::get('/memberships', 'getMemberships');
        });

        Route::controller(SubscriptionPlanController::class)->prefix('admin')->group(function () {
            Route::get('/subscription-plans', 'getSubscriptionPlans');
        });

        Route::controller(PaymentController::class)->prefix('admin')->group(function () {
            Route::post('/checkout', 'checkout');
        });

        Route::controller(UploadLogoController::class)->prefix('admin')->group(function () {
            Route::post('/upload-logo', 'uploadLogo');
        });

        Route::controller(OperationalSettingController::class)->prefix('admin')->group(function () {
            Route::post('/operational-settings-update', 'operationalSettingsUpdate');
            Route::get('/operational-settings', 'operationalSettings');
        });
    });

    Route::group(['middleware' => ['is_membership']], function () {
        Route::controller(DataSourceManagementController::class)->prefix('admin')->group(function () {
            Route::post('/data-source-management', 'dataSourceManagement');
            Route::get('/data-sources', 'getDataSources');
            Route::get('/data-source/{id}', 'getDataSource');
            Route::post('/data-source-update/{id}', 'updateDataSource');
            Route::delete('/data-source-delete/{id}', 'deleteDataSource');
        });

        Route::controller(DataSourcesController::class)->prefix('admin')->group(function () {
            Route::get('/data-sources-list', 'getDataSources');
        });
    });
    Route::controller(SupportController::class)->prefix('support')->group(function () {
        Route::post('/form-submit', 'supportForm');
    });

});

Route::controller(PaymentController::class)->group(function () {
    Route::get('/checkout-success', 'checkoutSuccess')->name('checkout.success');
    Route::get('/checkout-cancel', 'checkoutCancel')->name('checkout.cancel');
});

//shopware api
Route::controller(ShopwareController::class)->prefix('shopware')->group(function () {
    Route::get('test', 'testConnection');
});

//kpi dashboard api
Route::controller(KPIDashboardController::class)->prefix('kpi-dashboard')->group(function () {
    Route::post('store', 'store');
    Route::post('update', 'update');
    Route::get('show', 'index');
    Route::get('edit/{id}', 'edit');
    Route::delete('delete/{id}', 'destroy');
});
