<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleDashboardController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PledgeController;
use App\Http\Controllers\ReportingController;
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
    Route::get('/finance', function () {
        return view('dashboards.finance');
    })->name('finance.dashboard');
    Route::patch('donations/{donation}/validate', [DonationController::class, 'validateDonation'])->name('donations.validate');
});

Route::middleware(['auth', 'verified', 'role:Marketing'])->group(function (): void {
    Route::view('/marketing', 'dashboards.marketing')->name('marketing.dashboard');
});

Route::middleware(['auth', 'verified', 'role:Donor'])->group(function (): void {
    Route::get('/portal', [DonorPortalController::class, 'index'])->name('donor.portal');
    Route::post('/portal/donate/{campaign}', [DonorPortalController::class, 'donate'])->name('donor.donate');
    Route::get('/portal/receipt/{donation}', [DonorPortalController::class, 'receipt'])->name('donor.receipt');
});

Route::middleware(['auth', 'verified', 'role:Auditor'])->group(function (): void {
    Route::view('/auditor', 'dashboards.auditor')->name('auditor.dashboard');
});

Route::middleware('auth')->group(function () {
    // Donors cannot access the staff-facing donor management pages
    Route::middleware('role:Admin|Fundraising Manager|Finance|Marketing|Auditor')->group(function () {
        Route::resource('donors', DonorController::class);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Donations — accessible to Admin, Fundraising Manager, Finance
Route::middleware(['auth', 'verified', 'role:Admin|Fundraising Manager|Finance'])->group(function () {
    Route::resource('donations', DonationController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('donations/{donation}/receipt', [DonationController::class, 'receipt'])->name('donations.receipt');
});

// Pledges — accessible to Admin, Fundraising Manager
Route::middleware(['auth', 'verified', 'role:Admin|Fundraising Manager'])->group(function () {
    Route::resource('pledges', PledgeController::class)->except(['edit']);
});

// Reports — accessible to Admin, Finance, Auditor, Fundraising Manager
Route::middleware(['auth', 'verified', 'role:Admin|Finance|Auditor|Fundraising Manager'])->group(function () {
    Route::get('reports/donations', [ReportingController::class, 'donations'])->name('reports.donations');
    Route::get('reports/donors', [ReportingController::class, 'donors'])->name('reports.donors');
    Route::get('reports/campaigns', [ReportingController::class, 'campaigns'])->name('reports.campaigns');
    Route::get('reports/export/donations', [ReportingController::class, 'exportDonationsCsv'])->name('reports.export.donations');
    Route::get('reports/export/donors', [ReportingController::class, 'exportDonorsCsv'])->name('reports.export.donors');
});
