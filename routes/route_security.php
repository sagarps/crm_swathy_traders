<?php

use App\Http\Controllers\Security\SecurityDashboardController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth', 'Admin']], function () {
    Route::get('/security/dashboard', [SecurityDashboardController::class, 'index'])->name('security.dashboard');
    Route::get('/security/activities', [SecurityDashboardController::class, 'activities'])->name('security.activities');
    Route::get('/security/activities/{id}', [SecurityDashboardController::class, 'activityDetail'])->name('security.activities.detail');
    Route::get('/security/alerts', [SecurityDashboardController::class, 'alerts'])->name('security.alerts');
    Route::post('/security/alerts/{id}/resolve', [SecurityDashboardController::class, 'resolveAlert'])->name('security.alerts.resolve');
    Route::get('/security/health', [SecurityDashboardController::class, 'health'])->name('security.health');
});
