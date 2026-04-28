<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RumahController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\Auth\GoogleController;

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


Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('posts', PostController::class);
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
    // Admin
    Route::resource('admin/posts', PostController::class);
    Route::delete('/admin/post-image/{id}', [PostController::class, 'deleteImage'])
    ->name('post-image.delete');
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


Route::get('/autoshow', [HomeController::class, 'autoshow'])->name('autoshow');
Route::get('/autoshow/filter', [HomeController::class, 'autoshowFilter'])->name('autoshow.filter');

Route::get('/properti', [HomeController::class, 'properti'])->name('properti');
Route::get('/properti/filter', [HomeController::class, 'propertiFilter'])->name('properti.filter');

//halaman kategori
Route::get('/mobil', [HomeController::class, 'mobil'])->name('mobil.index');
Route::get('/motor', [HomeController::class, 'motor'])->name('motor.index');
Route::get('/rumah', [ListingController::class, 'rumah'])->name('rumah.index');
Route::get('/tanah', [HomeController::class, 'tanah'])->name('tanah.index');

// Remove conflicting route
// Route::get('/rumah', [ListingController::class, 'index'])
//     ->name('rumah.index');

// Berita detail
Route::get('/post/{id}', [PostController::class, 'show'])->name('post.show');

//ulasan

Route::prefix('testimoni')->name('testimoni.')->group(function () {
    Route::get('/', [TestimonialController::class, 'index'])->name('index');
    Route::get('/create', [TestimonialController::class, 'create'])->name('create');
    Route::post('/', [TestimonialController::class, 'store'])->name('store');
});

Route::middleware(['auth','admin'])->group(function () {
    Route::resource('admin/testimonials', TestimonialController::class)
        ->except(['create','store','show']);
});

Route::prefix('admin')->middleware(['auth'])->group(function () {

    Route::get('/testimonials', [\App\Http\Controllers\Admin\TestimonialController::class, 'index'])
        ->name('admin.testimonials.index');

    Route::patch('/testimonials/{id}/toggle', [\App\Http\Controllers\Admin\TestimonialController::class, 'toggle'])
        ->name('admin.testimonials.toggle');

    Route::delete('/testimonials/{id}', [\App\Http\Controllers\Admin\TestimonialController::class, 'destroy'])
        ->name('admin.testimonials.destroy');

});

Route::get('/about', function () {
    $posts = \App\Models\Post::latest()->take(5)->get();

    return view('about', compact('posts'));
})->name('about');

Route::view('/syarat', 'pages.terms')->name('terms');
Route::view('/kebijakan-privasi', 'pages.privacy')->name('privacy');

Route::prefix('kategori')->group(function () {
    Route::get('/tanah', [ListingController::class, 'tanah'])->name('tanah.index');
    Route::get('/mobil', [ListingController::class, 'mobil'])->name('mobil.index');
});

Route::get('/search', [ListingController::class, 'search'])->name('search');

Route::get('/tentang', function () {
    return view('tentang.index');
})->name('tentang');

//pinjam dana
Route::get('/pinjaman-dana', function () {
    return view('pinjaman.index');
})->name('pinjaman.index');

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);
require __DIR__.'/auth.php';
