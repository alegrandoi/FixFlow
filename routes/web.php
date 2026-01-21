<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $reportService = app(\App\Services\ReportService::class);
    $stats = $reportService->getGeneralStatistics();
    return view('dashboard', compact('stats'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('assets', AssetController::class);
    Route::resource('maintenances', MaintenanceController::class)->except(['edit', 'update']);
    
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/dashboard', [ReportController::class, 'index'])->name('dashboard');
        Route::get('/asset/{asset}/pdf', [ReportController::class, 'assetReport'])->name('asset.pdf');
        Route::get('/monthly-costs', [ReportController::class, 'monthlyCosts'])->name('monthly-costs');
        Route::get('/monthly-costs/export', [ReportController::class, 'exportMonthlyCosts'])->name('monthly-costs.export');
    });
});

require __DIR__.'/auth.php';
