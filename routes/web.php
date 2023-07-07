<?php

use App\Http\Controllers\ContentController;
use App\Http\Controllers\UserManagementActionController;
use App\Http\Controllers\UserProfileActionController;
use App\Http\Controllers\ViewController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

Route::group(['middleware' => ['guest:' . config('fortify.guard')]], static function() {
	Route::get('/invite/{token}', [ViewController::class, 'confirmRegistration'])->name('invite');
	Route::post('/invite/{token}', [UserProfileActionController::class, 'completeRegistration'])->name('invite.complete');
});
Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::group(['middleware' => 'auth'], static function() {
	Route::get('/', [ViewController::class, 'get'])->name('home');
	Route::group(['prefix' => '/content'], static function() {
		Route::post('/users', [ContentController::class, 'getUserManagementView'])->name('content.users');
		Route::post('/profile', [ContentController::class, 'getProfile'])->name('content.profile');
	});
	Route::group(['prefix' => '/user_profile'], static function() {
		Route::post('/update_password', [UserProfileActionController::class, 'changePassword'])->name('action.user-profile.change-password');
		Route::post('/refresh_two_factor_codes', [UserProfileActionController::class, 'refreshTwoFactorCodes'])->name('action.user-profile.refresh-two-factor-codes');
		Route::post('/invite_user', [UserProfileActionController::class, 'inviteNewUser',])->name('action.user-profile.invite-user');
	});
	Route::group(['prefix' => '/user_management'], static function() {
		Route::post('/delete_user', [UserManagementActionController::class, 'deleteUser',])->name('action.user-management.delete-user');
	});
});