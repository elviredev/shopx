<?php

use App\Http\Controllers\Frontend\KycController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\StoreController;
use App\Http\Controllers\Frontend\UserDashboardController;
use App\Http\Controllers\Frontend\VendorDashboardController;
use Illuminate\Support\Facades\Route;

/*================= Homepage =================*/
Route::get('/', function () {
    return view('frontend.home.index');
});

/*================= Dashboard USER/VENDOR =================*/

/** User routes */
Route::group(['middleware' => ['auth', 'verified']], function () {
  Route::get('/dashboard', [UserDashboardController::class, 'index'] )->name('dashboard');

  /** Profile routes */
  Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
  Route::put('/profile', [ProfileController::class, 'profileUpdate'])->name('profile.update');
  Route::put('/profile/password', [ProfileController::class, 'passwordUpdate'])->name('password.update');

  /** KYC routes */
  Route::get('/kyc-verification', [KycController::class, 'index'])->name('kyc.index');
  Route::post('/kyc-verification', [KycController::class, 'store'])->name('kyc.store');
});

/** Vendor routes */
Route::group(['prefix' => 'vendor', 'as' => 'vendor.', 'middleware' => ['auth', 'verified', 'user_role:vendor']], function () {
  Route::get('/dashboard', [VendorDashboardController::class, 'index'] )->name('dashboard');

  /** Shop Profile routes */
  Route::resource('store-profile', StoreController::class);
});

require __DIR__.'/auth.php';
