<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleDashboardController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DonorPortalController;
use App\Http\Controllers\CampaignController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/dashboard', RoleDashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified', 'role:Admin'])->group(function (): void {
    Route::view('/admin', 'dashboards.admin')->name('admin.dashboard');
    Route::resource('staff', StaffController::class)->only(['index', 'create', 'store', 'destroy']);
});

Route::middleware(['auth', 'verified', 'role_or_permission:Fundraising Manager|campaign.manage'])->group(function (): void {
    Route::view('/fundraising', 'dashboards.fundraising-manager')->name('fundraising.dashboard');
    Route::resource('campaigns', CampaignController::class);
});

Route::middleware(['auth', 'verified', 'role:Finance'])->group(function (): void {
    Route::view('/finance', 'dashboards.finance')->name('finance.dashboard');
});

Route::middleware(['auth', 'verified', 'role:Marketing'])->group(function (): void {
    Route::view('/marketing', 'dashboards.marketing')->name('marketing.dashboard');
});

Route::middleware(['auth', 'verified', 'role:Donor'])->group(function (): void {
    Route::get('/portal', [DonorPortalController::class, 'index'])->name('donor.portal');
    Route::post('/portal/donate/{campaign}', [DonorPortalController::class, 'donate'])->name('donor.donate');
});

Route::middleware(['auth', 'verified', 'role:Auditor'])->group(function (): void {
    Route::view('/auditor', 'dashboards.auditor')->name('auditor.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::resource('donors', DonorController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
