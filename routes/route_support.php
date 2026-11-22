<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Support\SupportTicketController;
use App\Http\Controllers\Support\AdminSupportTicketController;
use App\Http\Controllers\Support\SupportCategoryController;

Route::group(['middleware' => ['auth']], function () {

    // Common: Protected Attachment Download
    Route::get('/support/attachments/{id}/download', [SupportTicketController::class, 'downloadAttachment'])
        ->name('support.attachments.download');

    // -------------------------------------------------------------
    // CLIENT SUPPORT ROUTES (Scoped strictly to Auth::user()->office_id)
    // -------------------------------------------------------------
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/dashboard', [SupportTicketController::class, 'dashboard'])->name('dashboard');
        Route::get('/tickets', [SupportTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/create', [SupportTicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [SupportTicketController::class, 'store'])->name('tickets.store');
        Route::get('/tickets/{id}', [SupportTicketController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{id}/reply', [SupportTicketController::class, 'reply'])->name('tickets.reply');
        Route::post('/tickets/{id}/quotation/respond', [SupportTicketController::class, 'respondQuotation'])->name('tickets.quotation.respond');
        Route::post('/tickets/{id}/reopen', [SupportTicketController::class, 'requestReopen'])->name('tickets.reopen');
    });

    // -------------------------------------------------------------
    // ADMINISTRATOR / DEVELOPER SUPPORT ROUTES (Global Access)
    // -------------------------------------------------------------
    Route::prefix('admin/support')->name('admin.support.')->middleware(['support.admin'])->group(function () {
        Route::get('/dashboard', [AdminSupportTicketController::class, 'dashboard'])->name('dashboard');
        Route::get('/tickets', [AdminSupportTicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{id}', [AdminSupportTicketController::class, 'show'])->name('tickets.show');
        Route::post('/tickets/{id}/reply', [AdminSupportTicketController::class, 'reply'])->name('tickets.reply');
        Route::post('/tickets/{id}/internal-note', [AdminSupportTicketController::class, 'internalNote'])->name('tickets.internal_note');
        Route::post('/tickets/{id}/status', [AdminSupportTicketController::class, 'updateStatus'])->name('tickets.status.update');
        Route::post('/tickets/{id}/priority', [AdminSupportTicketController::class, 'updatePriority'])->name('tickets.priority.update');
        Route::post('/tickets/{id}/quotation', [AdminSupportTicketController::class, 'saveQuotation'])->name('tickets.quotation.save');
        Route::post('/tickets/{id}/actual-amount', [AdminSupportTicketController::class, 'updateActualAmount'])->name('tickets.actual_amount.update');
        Route::post('/tickets/{id}/payments', [AdminSupportTicketController::class, 'recordPayment'])->name('tickets.payments.store');

        // Categories Management
        Route::get('/categories', [SupportCategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [SupportCategoryController::class, 'store'])->name('categories.store');
        Route::post('/categories/{id}/update', [SupportCategoryController::class, 'update'])->name('categories.update');

        // Reports
        Route::get('/reports', [AdminSupportTicketController::class, 'reports'])->name('reports');
        Route::get('/reports/export-pdf', [AdminSupportTicketController::class, 'exportPdf'])->name('reports.export_pdf');
    });
});

