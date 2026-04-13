<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\CarouselController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth','admin'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('admin/listings', ListingController::class);
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('/admin/listings', App\Http\Controllers\ListingController::class);
    Route::get('/admin', [ListingController::class, 'index']);
    Route::get('/admin/create', [ListingController::class, 'create']);
    Route::post('/admin/store', [ListingController::class, 'store']);
    Route::get('/admin/edit/{id}', [ListingController::class, 'edit']);
    Route::put('/admin/update/{id}', [ListingController::class, 'update']);
    Route::delete('/admin/delete/{id}', [ListingController::class, 'destroy']);
    Route::get('/delete-image/{id}', [ListingController::class,'deleteImage'])->name('images.delete');

});
//edit carousel
Route::middleware(['auth','admin'])->group(function(){
    Route::get('/admin/carousel',[CarouselController::class,'index'])->name('carousel.index');
    Route::post('/admin/carousel',[CarouselController::class,'store'])->name('carousel.store');
    Route::delete('/admin/carousel/{id}',[CarouselController::class,'destroy'])->name('carousel.delete');
});
//beranda user
Route::get('/', [ListingController::class, 'home'])->name('home');
Route::get('/listing/{id}', [ListingController::class, 'show'])
    ->name('listing.show');
Route::get('/', [ListingController::class, 'home'])->name('home');

require __DIR__.'/auth.php';
