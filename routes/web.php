<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* --- 2. ADD YOUR TICKET ROUTES HERE --- */
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/archived', [TicketController::class, 'archived'])->name('tickets.archived');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');
    Route::delete('/tickets/attachments/{id}', [TicketController::class, 'destroyAttachment'])->name('tickets.attachments.destroy');
    Route::post('/tickets/{id}/restore', [TicketController::class, 'restore'])->name('tickets.restore');

    /* --- 3. EMPLOYEE ROUTES --- */
    Route::get('/employees', [App\Http\Controllers\EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/hierarchy', [App\Http\Controllers\HierarchyController::class, 'index'])->name('employees.hierarchy');
    Route::post('/employees/hierarchy', [App\Http\Controllers\HierarchyController::class, 'update'])->name('employees.hierarchy.update');
    Route::get('/employees/directory', [App\Http\Controllers\EmployeeController::class, 'directory'])->name('employees.directory');
    Route::get('/employees/create', [App\Http\Controllers\EmployeeController::class, 'create'])->name('employees.create');
    Route::get('/employees/{employee}', [App\Http\Controllers\EmployeeController::class, 'show'])->name('employees.show');
    Route::post('/employees', [App\Http\Controllers\EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}/edit', [App\Http\Controllers\EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [App\Http\Controllers\EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [App\Http\Controllers\EmployeeController::class, 'destroy'])->name('employees.destroy');
    Route::patch('/employees/{employee}/offboard', [App\Http\Controllers\EmployeeController::class, 'offboard'])->name('employees.offboard');
    
    Route::resource('sso_accounts', App\Http\Controllers\SsoAccountController::class);
    Route::post('/sso_accounts/{sso_account}/link', [App\Http\Controllers\SsoAccountController::class, 'linkEmployee'])->name('sso_accounts.link');
    Route::post('/sso_accounts/{sso_account}/mark-password-changed', [App\Http\Controllers\SsoAccountController::class, 'markPasswordChanged'])->name('sso_accounts.mark_password_changed');

    Route::get('/logs', [App\Http\Controllers\ActivityLogController::class, 'index'])->name('logs.index');

    /* --- REPORT ROUTES --- */
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/excel', [App\Http\Controllers\ReportController::class, 'exportExcel'])->name('reports.excel');
    Route::get('/reports/pdf', [App\Http\Controllers\ReportController::class, 'exportPdf'])->name('reports.pdf');
});

require __DIR__.'/auth.php';