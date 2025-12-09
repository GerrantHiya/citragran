<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ResidentController;
use App\Http\Controllers\Admin\IplBillController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\PayrollController;
use App\Http\Controllers\Admin\EmployeeDebtController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\FinancialReportController as AdminFinancialReportController;
use App\Http\Controllers\Resident\DashboardController as ResidentDashboardController;
use App\Http\Controllers\Resident\BillController;
use App\Http\Controllers\Resident\ReportController as ResidentReportController;
use App\Http\Controllers\Resident\FinancialReportController as ResidentFinancialReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Residents
    Route::resource('residents', ResidentController::class);

    // IPL Bills
    Route::get('ipl-bills/generate-bulk', [IplBillController::class, 'generateBulk'])->name('ipl-bills.generate-bulk');
    Route::post('ipl-bills/store-bulk', [IplBillController::class, 'storeBulk'])->name('ipl-bills.store-bulk');
    Route::post('ipl-bills/{iplBill}/payment', [IplBillController::class, 'addPayment'])->name('ipl-bills.add-payment');
    Route::resource('ipl-bills', IplBillController::class);

    // Reports (Complaints)
    Route::put('reports/{report}/status', [AdminReportController::class, 'updateStatus'])->name('reports.update-status');
    Route::put('reports/{report}/assign', [AdminReportController::class, 'assignTo'])->name('reports.assign');
    Route::post('reports/{report}/comment', [AdminReportController::class, 'addComment'])->name('reports.add-comment');
    Route::resource('reports', AdminReportController::class)->only(['index', 'show', 'destroy']);

    // Employees
    Route::resource('employees', EmployeeController::class);

    // Payrolls
    Route::get('payrolls/report', [PayrollController::class, 'report'])->name('payrolls.report');
    Route::post('payrolls/{payroll}/approve', [PayrollController::class, 'approve'])->name('payrolls.approve');
    Route::post('payrolls/{payroll}/pay', [PayrollController::class, 'pay'])->name('payrolls.pay');
    Route::post('payrolls/{payroll}/cancel', [PayrollController::class, 'cancel'])->name('payrolls.cancel');
    Route::resource('payrolls', PayrollController::class);

    // Employee Debts
    Route::post('employee-debts/{employeeDebt}/payment', [EmployeeDebtController::class, 'addPayment'])->name('employee-debts.add-payment');
    Route::post('employee-debts/{employeeDebt}/cancel', [EmployeeDebtController::class, 'cancel'])->name('employee-debts.cancel');
    Route::resource('employee-debts', EmployeeDebtController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

    // Expenses
    Route::get('expenses/report', [ExpenseController::class, 'report'])->name('expenses.report');
    Route::post('expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
    Route::post('expenses/{expense}/reject', [ExpenseController::class, 'reject'])->name('expenses.reject');
    Route::resource('expenses', ExpenseController::class);

    // Financial Reports
    Route::get('financial-reports/income', [AdminFinancialReportController::class, 'incomeReport'])->name('financial-reports.income');
    Route::post('financial-reports/generate', [AdminFinancialReportController::class, 'generate'])->name('financial-reports.generate');
    Route::post('financial-reports/{financialReport}/publish', [AdminFinancialReportController::class, 'publish'])->name('financial-reports.publish');
    Route::post('financial-reports/{financialReport}/unpublish', [AdminFinancialReportController::class, 'unpublish'])->name('financial-reports.unpublish');
    Route::put('financial-reports/{financialReport}/summary', [AdminFinancialReportController::class, 'updateSummary'])->name('financial-reports.update-summary');
    Route::resource('financial-reports', AdminFinancialReportController::class)->only(['index', 'show']);
});

// Resident Routes
Route::prefix('resident')->name('resident.')->middleware('resident')->group(function () {
    Route::get('/', [ResidentDashboardController::class, 'index'])->name('dashboard');

    // Bills
    Route::get('bills', [BillController::class, 'index'])->name('bills.index');
    Route::get('bills/history', [BillController::class, 'history'])->name('bills.history');
    Route::get('bills/{bill}', [BillController::class, 'show'])->name('bills.show');

    // Reports
    Route::post('reports/{report}/comment', [ResidentReportController::class, 'addComment'])->name('reports.add-comment');
    Route::resource('reports', ResidentReportController::class)->only(['index', 'create', 'store', 'show']);

    // Financial Reports & Announcements
    Route::get('financial-reports', [ResidentFinancialReportController::class, 'index'])->name('financial-reports.index');
    Route::get('financial-reports/{financialReport}', [ResidentFinancialReportController::class, 'show'])->name('financial-reports.show');
    Route::get('announcements', [ResidentFinancialReportController::class, 'announcements'])->name('announcements');
});

