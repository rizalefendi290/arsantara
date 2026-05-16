<?php

use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\SalesManagementController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Agent\AgentDashboardController;
use App\Http\Controllers\Agent\AgentListingController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\Marketing\SaleController;
use App\Http\Controllers\OrganizationMemberController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestimonialController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ListingController::class, 'home'])->name('home');

Route::get('/listing/{id}', [ListingController::class, 'show'])->name('listing.show');
Route::get('/post/{id}', [PostController::class, 'show'])->name('post.show');

Route::get('/autoshow', [HomeController::class, 'autoshow'])->name('autoshow');
Route::get('/autoshow/filter', [HomeController::class, 'autoshowFilter'])->name('autoshow.filter');
Route::get('/properti', [HomeController::class, 'properti'])->name('properti');
Route::get('/properti/filter', [HomeController::class, 'propertiFilter'])->name('properti.filter');

Route::get('/mobil', [HomeController::class, 'mobil'])->name('mobil.index');
Route::get('/motor', [HomeController::class, 'motor'])->name('motor.index');
Route::get('/rumah', [ListingController::class, 'rumah'])->name('rumah.index');
Route::get('/tanah', [HomeController::class, 'tanah'])->name('tanah.index');
Route::redirect('/kategori/mobil', '/mobil');
Route::redirect('/kategori/tanah', '/tanah');
Route::get('/kategori/{slug}', [HomeController::class, 'category'])->name('category.show');

Route::get('/search', [ListingController::class, 'search'])->name('search');

Route::prefix('testimoni')->name('testimoni.')->group(function () {
    Route::get('/', [TestimonialController::class, 'index'])->name('index');
    Route::get('/create', [TestimonialController::class, 'create'])->name('create');
    Route::post('/', [TestimonialController::class, 'store'])->name('store');
});

Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::view('/syarat', 'pages.terms')->name('terms');
Route::view('/kebijakan-privasi', 'pages.privacy')->name('privacy');
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/pasang-iklan', 'pages.pasang-iklan')->name('ads.guide');
Route::view('/tentang', 'tentang.index')->name('tentang');
Route::view('/pinjaman-dana', 'pinjaman.index')->name('pinjaman.index');

Route::get('/api/wilayah/provinces', [CareerController::class, 'wilayahProvinces'])->name('wilayah.provinces');
Route::get('/api/wilayah/regencies/{provinceCode}', [CareerController::class, 'wilayahRegencies'])->name('wilayah.regencies');
Route::get('/api/wilayah/districts/{regencyCode}', [CareerController::class, 'wilayahDistricts'])->name('wilayah.districts');
Route::get('/api/wilayah/villages/{districtCode}', [CareerController::class, 'wilayahVillages'])->name('wilayah.villages');

Route::get('/karir', [CareerController::class, 'index'])->name('careers.index');
Route::get('/karir/{jobVacancy}', [CareerController::class, 'show'])->name('careers.show');
Route::get('/karir/{jobVacancy}/lamar', [CareerController::class, 'apply'])->name('careers.apply');
Route::post('/karir/{jobVacancy}/lamar', [CareerController::class, 'submitApplication'])->name('careers.apply.submit');

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

Route::view('/dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/upgrade-role', [ProfileController::class, 'upgradeRole'])->name('upgrade.role');
    Route::post('/submit-request', [ProfileController::class, 'submitRequest'])->name('submit.request');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('admin/listings', ListingController::class)->names('listings');
    Route::get('/admin/listing-exports/excel', [ListingController::class, 'exportExcel'])->name('admin.listings.export.excel');
    Route::get('/admin/listing-exports/pdf', [ListingController::class, 'exportPdf'])->name('admin.listings.export.pdf');
    Route::patch('/admin/listings/{id}/approve', [ListingController::class, 'approve'])->name('admin.listings.approve');
    Route::delete('/delete-image/{id}', [ListingController::class, 'deleteImage'])->name('images.delete');

    Route::resource('admin/posts', PostController::class)->names('posts');
    Route::delete('/admin/post-image/{id}', [PostController::class, 'deleteImage'])->name('post-image.delete');

    Route::get('/admin/carousel', [CarouselController::class, 'index'])->name('carousel.index');
    Route::post('/admin/carousel', [CarouselController::class, 'store'])->name('carousel.store');
    Route::patch('/admin/carousel/{id}', [CarouselController::class, 'update'])->name('carousel.update');
    Route::delete('/admin/carousel/{id}', [CarouselController::class, 'destroy'])->name('carousel.delete');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [ListingController::class, 'index'])->name('index');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::patch('/categories/{category}/toggle', [AdminDashboardController::class, 'toggleCategory'])->name('categories.toggle');

        Route::get('/recommendations', [ListingController::class, 'recommendations'])->name('recommendations.index');
        Route::patch('/recommendations/{id}/toggle', [ListingController::class, 'toggleRecommendation'])->name('recommendations.toggle');

        Route::get('/users', [AdminController::class, 'index'])->name('users');
        Route::get('/role-requests', [AdminController::class, 'requests'])->name('role-requests.index');
        Route::patch('/users/{id}', [AdminController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('users.destroy');
        Route::post('/approve/{id}', [AdminController::class, 'approve'])->name('users.approve');
        Route::post('/reject/{id}', [AdminController::class, 'reject'])->name('users.reject');

        Route::get('/organization', [OrganizationMemberController::class, 'index'])->name('organization.index');
        Route::post('/organization', [OrganizationMemberController::class, 'store'])->name('organization.store');
        Route::patch('/organization/{organization}', [OrganizationMemberController::class, 'update'])->name('organization.update');
        Route::delete('/organization/{organization}', [OrganizationMemberController::class, 'destroy'])->name('organization.destroy');

        Route::get('/partners', [PartnerController::class, 'index'])->name('partners.index');
        Route::post('/partners', [PartnerController::class, 'store'])->name('partners.store');
        Route::patch('/partners/{partner}', [PartnerController::class, 'update'])->name('partners.update');
        Route::delete('/partners/{partner}', [PartnerController::class, 'destroy'])->name('partners.destroy');

        Route::get('/careers', [CareerController::class, 'adminIndex'])->name('careers.index');
        Route::get('/careers/applications', [CareerController::class, 'adminApplications'])->name('careers.applications');
        Route::get('/careers/applications/{jobApplication}', [CareerController::class, 'showApplication'])->name('careers.applications.show');
        Route::get('/careers/applications/{jobApplication}/cv', [CareerController::class, 'downloadApplicationCv'])->name('careers.applications.cv');
        Route::patch('/careers/applications/{jobApplication}/accept', [CareerController::class, 'acceptApplication'])->name('careers.applications.accept');
        Route::patch('/careers/applications/{jobApplication}/reject', [CareerController::class, 'rejectApplication'])->name('careers.applications.reject');
        Route::delete('/careers/applications/{jobApplication}', [CareerController::class, 'destroyApplication'])->name('careers.applications.destroy');
        Route::post('/careers', [CareerController::class, 'store'])->name('careers.store');
        Route::patch('/careers/{jobVacancy}', [CareerController::class, 'update'])->name('careers.update');
        Route::delete('/careers/{jobVacancy}', [CareerController::class, 'destroy'])->name('careers.destroy');

        Route::get('/testimonials', [AdminTestimonialController::class, 'index'])->name('testimonials.index');
        Route::patch('/testimonials/{id}/toggle', [AdminTestimonialController::class, 'toggle'])->name('testimonials.toggle');
        Route::delete('/testimonials/{id}', [AdminTestimonialController::class, 'destroy'])->name('testimonials.destroy');

        Route::prefix('sales')->name('sales.')->group(function () {
            Route::get('/', [SalesManagementController::class, 'dashboard'])->name('dashboard');
            Route::get('/list', [SalesManagementController::class, 'sales'])->name('list');
            Route::get('/export/excel', [SalesManagementController::class, 'exportExcel'])->name('export.excel');
            Route::get('/export/pdf', [SalesManagementController::class, 'exportPdf'])->name('export.pdf');
            Route::post('/{sale}/approve', [SalesManagementController::class, 'approve'])->name('approve');
            Route::post('/{sale}/reject', [SalesManagementController::class, 'reject'])->name('reject');
            Route::post('/{sale}/cancel', [SalesManagementController::class, 'cancel'])->name('cancel');
            Route::get('/marketing', [SalesManagementController::class, 'marketing'])->name('marketing');
            Route::post('/marketing', [SalesManagementController::class, 'storeMarketing'])->name('marketing.store');
            Route::patch('/marketing/{user}', [SalesManagementController::class, 'updateMarketing'])->name('marketing.update');
            Route::get('/commissions', [SalesManagementController::class, 'commissionRules'])->name('commissions');
            Route::patch('/commissions', [SalesManagementController::class, 'updateCommissionRules'])->name('commissions.update');
            Route::get('/targets', [SalesManagementController::class, 'targets'])->name('targets');
            Route::post('/targets', [SalesManagementController::class, 'storeTarget'])->name('targets.store');
            Route::delete('/targets/{target}', [SalesManagementController::class, 'destroyTarget'])->name('targets.destroy');
        });
    });
});

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

Route::middleware(['auth', 'approved', 'marketing'])->prefix('marketing')->name('marketing.')->group(function () {
    Route::get('/dashboard', [SaleController::class, 'dashboard'])->name('dashboard');
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::post('/sales/{sale}/cancel', [SaleController::class, 'cancel'])->name('sales.cancel');
});
