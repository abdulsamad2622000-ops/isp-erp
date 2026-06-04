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
    Route::resource('users', UserController::class);
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('areas', AreaController::class);
    Route::resource('packages', PackageController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('connections', ConnectionController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('complaints', ComplaintController::class);
    Route::resource('inventory', InventoryController::class);
    Route::resource('suspensions', SuspensionController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('notifications', NotificationController::class);
});