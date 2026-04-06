<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\Admin\EditorImageController;
use App\Http\Controllers\Admin\EditorImageUploadController;
use App\Http\Controllers\Admin\EditorUploadController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/api/homepage/companies/{sort}', [HomeController::class, 'getCompaniesBySort'])->name('api.homepage.companies');
Route::get('/api/companies/{sort}', [CompaniesController::class, 'getCompaniesBySort'])->name('api.companies');
Route::get('/companies', [CompaniesController::class, 'index'])->name('companies.index');
Route::get('/companies/{company:slug}', [CompanyController::class, 'show'])->name('companies.show');
Route::get('/companies/{company:slug}/reviews', [CompanyController::class, 'reviews'])->name('companies.reviews');
Route::post('/compare/add/{company}', [CompareController::class, 'add'])->name('compare.add');
Route::delete('/compare/remove/{company}', [CompareController::class, 'remove'])->name('compare.remove');
Route::get('/compare', [CompareController::class, 'index'])->name('compare.index');
Route::get('/quote', [QuoteController::class, 'create'])->name('quote.create');
Route::post('/quote', [QuoteController::class, 'store'])->name('quote.store');
Route::get('/quote/complete', [QuoteController::class, 'complete'])->name('quote.complete');

// Contact routes
Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/contact/complete', [ContactController::class, 'complete'])->name('contact.complete');

// News routes
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{article:slug}', [NewsController::class, 'show'])->name('news.show');

// Review routes
Route::get('/reviews', [ReviewController::class, 'selectCompany'])->name('reviews.index');
Route::get('/reviews/select-company', [ReviewController::class, 'selectCompany'])->name('reviews.select-company');
Route::get('/reviews/create/{company:slug}', [ReviewController::class, 'create'])->name('reviews.create');
Route::post('/reviews/{company:slug}', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('/reviews/complete/{company:slug}', [ReviewController::class, 'complete'])->name('reviews.complete');

// Guide routes
Route::get('/guide/window-cleaning-price', [GuideController::class, 'windowCleaningPrice'])->name('guide.window-cleaning-price');
Route::get('/guide/window-cleaning-contractor-selection', [GuideController::class, 'windowCleaningContractorSelection'])->name('guide.window-cleaning-contractor-selection');
Route::get('/guide/exterior-wall-painting-pricing', [GuideController::class, 'exteriorWallPaintingPricing'])->name('guide.exterior-wall-painting-pricing');
Route::get('/guide/exterior-wall-painting-contractor-selection', [GuideController::class, 'exteriorWallPaintingContractorSelection'])->name('guide.exterior-wall-painting-contractor-selection');

// Admin Editor Image Upload Routes (Filament管理者のみ)
Route::middleware(['web', 'auth'])->prefix('admin')->group(function () {
    Route::post('/upload-image', [\App\Http\Controllers\Admin\EditorImageController::class, 'upload'])->name('admin.upload-image');
    Route::post('/editor/upload-image', [\App\Http\Controllers\Admin\EditorUploadController::class, 'uploadImage'])->name('admin.editor.upload-image');
    Route::delete('/editor/delete-image', [\App\Http\Controllers\Admin\EditorImageUploadController::class, 'delete'])->name('admin.editor.delete-image');
    
    // テスト用エンドポイント
    Route::get('/test-upload', function() {
        return response()->json(['message' => 'Test endpoint working', 'timestamp' => now()]);
    });
    Route::post('/test-upload', [\App\Http\Controllers\TestUploadController::class, 'test'])->name('admin.test-upload');
});
