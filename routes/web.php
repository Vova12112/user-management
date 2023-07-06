<?php

use App\Http\Controllers\ViewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ViewController::class, 'get'])->name('home');
Route::group(['prefix' => '/content'], static function() {
	Route::post('/users', [ViewController::class, 'usersContent'])->name('content.users');
	Route::post('/profile', [ViewController::class, 'profileContent'])->name('content.profile');
});