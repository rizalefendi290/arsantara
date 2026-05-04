<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RumahController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Agent\AgentDashboardController;
use App\Http\Controllers\Agent\AgentListingController;
use App\Http\Controllers\OrganizationMemberController;
use App\Http\Controllers\PartnerController;

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
    Route::post('/upgrade-role', [ProfileController::class, 'upgradeRole'])->name('upgrade.role');
    Route::post('/submit-request', [ProfileController::class, 'submitRequest'])->name('submit.request');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('admin/listings', ListingController::class);
});


Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('posts', PostController::class);
});



Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::patch('/admin/categories/{category}/toggle', [App\Http\Controllers\AdminDashboardController::class, 'toggleCategory'])->name('admin.categories.toggle');
    Route::get('/admin/listing-exports/excel', [ListingController::class, 'exportExcel'])->name('admin.listings.export.excel');
    Route::get('/admin/listing-exports/pdf', [ListingController::class, 'exportPdf'])->name('admin.listings.export.pdf');
    Route::resource('/admin/listings', App\Http\Controllers\ListingController::class);
    Route::get('/admin', [ListingController::class, 'index']);
    Route::get('/admin/create', [ListingController::class, 'create']);
    Route::post('/admin/store', [ListingController::class, 'store']);
    Route::get('/admin/edit/{id}', [ListingController::class, 'edit']);
    Route::put('/admin/update/{id}', [ListingController::class, 'update']);
    Route::patch('/admin/listings/{id}/approve', [ListingController::class, 'approve'])->name('admin.listings.approve');
    Route::get('/admin/recommendations', [ListingController::class, 'recommendations'])->name('admin.recommendations.index');
    Route::patch('/admin/recommendations/{id}/toggle', [ListingController::class, 'toggleRecommendation'])->name('admin.recommendations.toggle');
    Route::delete('/admin/delete/{id}', [ListingController::class, 'destroy']);
    Route::get('/delete-image/{id}', [ListingController::class,'deleteImage'])->name('images.delete');
    // Admin
    Route::resource('admin/posts', PostController::class);
    Route::delete('/admin/post-image/{id}', [PostController::class, 'deleteImage'])->name('post-image.delete');
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users');
    Route::patch('/admin/users/{id}', [AdminController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/admin/approve/{id}', [AdminController::class, 'approve'])->name('admin.users.approve');
    Route::post('/admin/reject/{id}', [AdminController::class, 'reject'])->name('admin.users.reject');
    Route::get('/admin/organization', [OrganizationMemberController::class, 'index'])->name('admin.organization.index');
    Route::post('/admin/organization', [OrganizationMemberController::class, 'store'])->name('admin.organization.store');
    Route::patch('/admin/organization/{organization}', [OrganizationMemberController::class, 'update'])->name('admin.organization.update');
    Route::delete('/admin/organization/{organization}', [OrganizationMemberController::class, 'destroy'])->name('admin.organization.destroy');
    Route::get('/admin/partners', [PartnerController::class, 'index'])->name('admin.partners.index');
    Route::post('/admin/partners', [PartnerController::class, 'store'])->name('admin.partners.store');
    Route::patch('/admin/partners/{partner}', [PartnerController::class, 'update'])->name('admin.partners.update');
    Route::delete('/admin/partners/{partner}', [PartnerController::class, 'destroy'])->name('admin.partners.destroy');
});
//edit carousel
Route::middleware(['auth','admin'])->group(function(){
    Route::get('/admin/carousel',[CarouselController::class,'index'])->name('carousel.index');
    Route::post('/admin/carousel',[CarouselController::class,'store'])->name('carousel.store');
    Route::patch('/admin/carousel/{id}',[CarouselController::class,'update'])->name('carousel.update');
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
    $posts = \App\Models\Post::with('images')->latest()->take(5)->get();
    $organizationMembers = \App\Models\OrganizationMember::where('is_active', true)
        ->orderBy('sort_order')
        ->latest()
        ->get();
    $partners = \App\Models\Partner::where('is_active', true)
        ->orderBy('sort_order')
        ->latest()
        ->get();

    return view('about', compact('posts', 'organizationMembers', 'partners'));
})->name('about');

Route::view('/syarat', 'pages.terms')->name('terms');
Route::view('/kebijakan-privasi', 'pages.privacy')->name('privacy');
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/pasang-iklan', 'pages.pasang-iklan')->name('ads.guide');

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
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

Route::middleware(['auth', 'approved', 'agent.owner'])->group(function () {
    Route::get('/dashboard-agen', [AgentDashboardController::class, 'index'])->name('agent.dashboard');
    Route::get('/dashboard-pemilik', [AgentDashboardController::class, 'index'])->name('owner.dashboard');
    Route::get('/agen/listings/create', [AgentListingController::class, 'create'])->name('agent.listings.create');
    Route::post('/agen/listings', [AgentListingController::class, 'store'])->name('agent.listings.store');
    Route::get('/agen/listings/{listing}/edit', [AgentListingController::class, 'edit'])->name('agent.listings.edit');
    Route::put('/agen/listings/{listing}', [AgentListingController::class, 'update'])->name('agent.listings.update');
    Route::delete('/agen/listings/{listing}', [AgentListingController::class, 'destroy'])->name('agent.listings.destroy');
    Route::patch('/agen/listings/{listing}/sold', [AgentListingController::class, 'markSold'])->name('agent.listings.sold');
    Route::delete('/agen/listing-images/{image}', [AgentListingController::class, 'deleteImage'])->name('agent.listings.images.destroy');
});
require __DIR__.'/auth.php';
