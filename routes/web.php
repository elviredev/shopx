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

  /** Products Routes */
  Route::get('/products', [\App\Http\Controllers\Frontend\VendorProductController::class, 'index'])->name('products.index');
  Route::get('/products/{type}/create', [\App\Http\Controllers\Frontend\VendorProductController::class, 'create'])->name('products.create');
  Route::post('/products/{type}/create', [\App\Http\Controllers\Frontend\VendorProductController::class, 'store'])->name('products.store');
  Route::get('/products/physical/{product}/edit', [\App\Http\Controllers\Frontend\VendorProductController::class, 'edit'])->name('products.edit');
  Route::post('/products/physical/{product}/update', [\App\Http\Controllers\Frontend\VendorProductController::class, 'update'])->name('products.update');
  Route::post('/products/images/upload/{product}', [\App\Http\Controllers\Frontend\VendorProductController::class, 'uploadImages'])->name('products.images.upload');
  Route::delete('/products/images/{image}', [\App\Http\Controllers\Frontend\VendorProductController::class, 'destroyImage'])->name('products.images.destroy');
  Route::post('/products/images/reorder', [\App\Http\Controllers\Frontend\VendorProductController::class, 'reorderImages'])->name('products.images.reorder');

  /** Products Attributes Routes */
  Route::post('/products/attributes/{product}/store', [\App\Http\Controllers\Frontend\VendorProductController::class, 'storeAttributes'])
    ->name('products.attributes.store');
  Route::delete('/products/attributes/{product}/{attribute}', [\App\Http\Controllers\Frontend\VendorProductController::class, 'destroyAttribute'])
    ->name('products.attributes.destroy');

  /** Products Variants Routes */
  Route::post('/products/variants/{product}/update', [\App\Http\Controllers\Frontend\VendorProductController::class, 'updateVariants'])
    ->name('products.variants.update');

  /** Digital Products Routes */
  Route::get('/products/digital/{product}/edit', [\App\Http\Controllers\Frontend\VendorProductController::class, 'editDigitalProduct'])->name('digital-products.edit');
  Route::post('/products/digital/file-upload', [\App\Http\Controllers\Frontend\VendorProductController::class, 'uploadDigitalProductFile'])->name('digital-products.file-upload');
  Route::delete('/products/digital/{product}/{file}', [\App\Http\Controllers\Frontend\VendorProductController::class, 'destroyDigitalProductFile'])->name('digital-products.file.destroy');

  Route::delete('/products/{product}', [\App\Http\Controllers\Frontend\VendorProductController::class, 'destroy'])->name('products.destroy');
});

require __DIR__.'/auth.php';
