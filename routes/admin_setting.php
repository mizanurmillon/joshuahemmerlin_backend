<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Backend\Settings\ProfileController;
use App\Http\Controllers\Web\Backend\Settings\MailSettingController;
use App\Http\Controllers\Web\Backend\Settings\StripeSettingController;
use App\Http\Controllers\Web\Backend\Settings\SystemSettingController;


//! This route is for updating the user's profile
Route::controller(ProfileController::class)->group(function () {
    Route::post('/update-profile-picture', 'UpdateProfilePicture')->name('update.profile.picture');
    Route::post('/update-profile-password', 'UpdatePassword')->name('update.Password');

    //! Route for ProfileController
    Route::get('/profile', 'showProfile')->name('profile.setting');
    Route::post('/update-profile', 'UpdateProfile')->name('update.profile');
});

//! Route for SystemSettingController
Route::controller(SystemSettingController::class)->group(function () {
    Route::get('/system-setting', 'index')->name('system.index');
    Route::post('/system-setting', 'update')->name('system.update');
});

//! Route for MailSettingController
Route::controller(MailSettingController::class)->group(function () {
    Route::get('/mail-setting', 'index')->name('mail.setting');
    Route::post('/mail-setting', 'update')->name('mail.update');
});


Route::controller(StripeSettingController::class)->group(function () {
    Route::get('/stripe-setting', 'index')->name('stripe.settings');
    Route::post('/stripe-setting', 'update')->name('stripe.update');
});