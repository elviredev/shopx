<?php

use App\Http\Controllers\Frontend\UserDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('frontend.home.index');
});

/*================= Dashboard USER/VENDOR =================*/
Route::group(['middleware' => ['auth', 'verified']], function () {
  Route::get('/dashboard', [UserDashboardController::class, 'index'] )->name('dashboard');
});

require __DIR__.'/auth.php';
