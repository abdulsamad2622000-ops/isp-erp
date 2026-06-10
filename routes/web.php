<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ConnectionController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SuspensionController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('areas', AreaController::class);
    Route::resource('packages', PackageController::class);

    Route::resource('customers', CustomerController::class);
    Route::get('customers-export', [CustomerController::class, 'export'])->name('customers.export');
    Route::post('customers-import', [CustomerController::class, 'import'])->name('customers.import');

    Route::resource('connections', ConnectionController::class);

    Route::post('invoices/bulk-paid', [InvoiceController::class, 'bulkPaid'])->name('invoices.bulkPaid');
    Route::resource('invoices', InvoiceController::class);
    Route::post('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.markPaid');
    Route::post('invoices/{invoice}/partial-payment', [InvoiceController::class, 'partialPayment'])->name('invoices.partialPayment');

    Route::resource('payments', PaymentController::class);
    Route::resource('complaints', ComplaintController::class);
    Route::resource('inventory', InventoryController::class);
    Route::resource('suspensions', SuspensionController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('notifications', NotificationController::class);
});