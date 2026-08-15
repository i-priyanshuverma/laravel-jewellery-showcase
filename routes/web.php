<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\ProductListingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\StorageFallbackController;
use App\Http\Controllers\Vendor;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductListingController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductListingController::class, 'show'])->name('products.show');
Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
Route::post('/reservations', [ReservationController::class, 'store'])
    ->middleware('throttle:reservations')
    ->name('reservations.store');
Route::delete('/reservations/release-all', [ReservationController::class, 'destroyAll'])
    ->name('reservations.destroyAll');
Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])
    ->name('reservations.destroy');

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('vendor.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/dashboard');
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/vendors', [Admin\VendorController::class, 'index'])->name('vendors.index');
    Route::get('/vendors/{vendor}', [Admin\VendorController::class, 'show'])->name('vendors.show');
    Route::patch('/vendors/{vendor}/approve', [Admin\VendorController::class, 'approve'])->name('vendors.approve');
    Route::patch('/vendors/{vendor}/suspend', [Admin\VendorController::class, 'suspend'])->name('vendors.suspend');
    Route::patch('/vendors/{vendor}/reactivate', [Admin\VendorController::class, 'reactivate'])->name('vendors.reactivate');

    Route::resource('categories', Admin\CategoryController::class)->except(['show']);
    Route::resource('metals', Admin\MetalController::class)->except(['show']);
    Route::resource('colours', Admin\ColourController::class)->except(['show']);
    Route::resource('sizes', Admin\JewellerySizeController::class)->except(['show']);
    Route::resource('stone-types', Admin\StoneTypeController::class)->except(['show']);

    Route::get('/products', [Admin\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [Admin\ProductController::class, 'show'])->name('products.show');

    Route::get('/active-holds-count', [Admin\DashboardController::class, 'activeHoldsCount'])->name('active-holds-count');
});

Route::middleware(['auth'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::redirect('/', '/vendor/dashboard');
    Route::get('/dashboard', [Vendor\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [Vendor\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [Vendor\ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'vendor.approved'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::resource('products', Vendor\ProductController::class);

    Route::get('/api/metals/{metal}/purities', [Admin\MetalController::class, 'purities'])->name('api.metal.purities');

    Route::get('/products/{product}/images', [Vendor\ProductImageController::class, 'index'])->name('products.images.index');
    Route::post('/products/{product}/images', [Vendor\ProductImageController::class, 'store'])->name('products.images.store');
    Route::delete('/products/{product}/images/{image}', [Vendor\ProductImageController::class, 'destroy'])->name('products.images.destroy');

    Route::resource('products.variants', Vendor\ProductVariantController::class)->except(['index', 'show']);

    Route::get('/imports', [Vendor\CsvImportController::class, 'index'])->name('imports.index');
    Route::get('/imports/create', [Vendor\CsvImportController::class, 'create'])->name('imports.create');
    Route::get('/imports/sample-csv', [Vendor\CsvImportController::class, 'sampleCsv'])->name('imports.sample');
    Route::post('/imports', [Vendor\CsvImportController::class, 'store'])
        ->middleware('throttle:csv-imports')
        ->name('imports.store');
    Route::get('/imports/{import}', [Vendor\CsvImportController::class, 'show'])->name('imports.show');
    Route::get('/imports/{import}/progress', [Vendor\CsvImportController::class, 'progress'])->name('imports.progress');
});

Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, ['en', 'hi', 'ar'], true)) {
        session(['locale' => $locale]);
        cookie()->queue(cookie()->forever('locale', $locale));
    }

    return back();
})->name('locale.switch');

Route::get('/storage/{path}', [StorageFallbackController::class, 'show'])
    ->where('path', '.*')
    ->name('storage.fallback');

require __DIR__.'/auth.php';
