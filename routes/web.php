<?php

use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Frontend\UserDashboardController;
use Illuminate\Support\Facades\Route;

/*================= Homepage =================*/
Route::get('/', function () {
    return view('frontend.home.index');
});

/*================= Dashboard USER/VENDOR =================*/
Route::group(['middleware' => ['auth', 'verified']], function () {
  Route::get('/dashboard', [UserDashboardController::class, 'index'] )->name('dashboard');

  /** Profile routes */
  Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
  Route::put('/profile', [ProfileController::class, 'profileUpdate'])->name('profile.update');
  Route::put('/profile/password', [ProfileController::class, 'passwordUpdate'])->name('password.update');
});

require __DIR__.'/auth.php';
