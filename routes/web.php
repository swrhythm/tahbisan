<?php

use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventScheduleItemController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Frater\BiographyController;
use App\Http\Controllers\Frater\BiographyImageController;
use App\Http\Controllers\Frater\DashboardController as FraterDashboardController;
use App\Http\Controllers\Frater\ScheduleItemController;
use App\Http\Controllers\Frater\WishlistItemController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\WishlistClaimController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'landing'])->name('public.landing');
Route::get('/tahbisan/{event}', [PublicController::class, 'event'])->name('public.event');
Route::get('/calon/{candidate}', [PublicController::class, 'profile'])->name('public.profile');
Route::post('/wishlist/{wishlistItem}/claim', [WishlistClaimController::class, 'store'])->name('wishlist.claim');

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login/frater', [LoginController::class, 'frater'])->name('login.frater');
Route::post('/login/admin', [LoginController::class, 'admin'])->name('login.admin');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth:frater')->prefix('dashboard')->name('frater.')->group(function () {
    Route::get('/', [FraterDashboardController::class, 'index'])->name('dashboard');

    Route::post('/informasi', [ScheduleItemController::class, 'store'])->name('informasi.store');
    Route::put('/informasi/{scheduleItem}', [ScheduleItemController::class, 'update'])->name('informasi.update');
    Route::delete('/informasi/{scheduleItem}', [ScheduleItemController::class, 'destroy'])->name('informasi.destroy');

    Route::post('/biography', [BiographyController::class, 'update'])->name('biography.update');
    Route::post('/biography/image', [BiographyImageController::class, 'store'])->name('biography.image');

    Route::post('/wishlist', [WishlistItemController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{wishlistItem}', [WishlistItemController::class, 'destroy'])->name('wishlist.destroy');
});

Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

    Route::post('/candidates', [CandidateController::class, 'store'])->name('candidates.store');
    Route::delete('/candidates/{candidate}', [CandidateController::class, 'destroy'])->name('candidates.destroy');

    Route::post('/schedule', [EventScheduleItemController::class, 'store'])->name('schedule.store');
    Route::delete('/schedule/{eventScheduleItem}', [EventScheduleItemController::class, 'destroy'])->name('schedule.destroy');
});
