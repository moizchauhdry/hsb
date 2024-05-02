<?php

use App\Http\Controllers\CustomerAccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkOrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);
});

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Work Order
    Route::get('/work-order', [WorkOrderController::class, 'index'])->name('work-order.index');
    Route::get('/work-order/create', [WorkOrderController::class, 'create'])->name('work-order.create');
    Route::get('/work-order/edit/{id}', [WorkOrderController::class, 'edit'])->name('work-order.edit');
    Route::post('/work-order/update', [WorkOrderController::class, 'update'])->name('work-order.update');
    Route::post('/work-order/complete', [WorkOrderController::class, 'complete'])->name('work-order.complete');
    Route::post('/work-order/delete', [WorkOrderController::class, 'delete'])->name('work-order.delete');
    Route::get('/work-order/label/{id}', [WorkOrderController::class, 'label'])->name('work-order.label');
    Route::get('/work-order/pending', [WorkOrderController::class, 'pending'])->name('work-order.pending');
    Route::get('/work-order/detail/{id}', [WorkOrderController::class, 'detail'])->name('work-order.detail');

    // Customer Account
    Route::get('/customer-account', [CustomerAccountController::class, 'index'])->name('customer-account.index');
    Route::get('/customer-account/create', [CustomerAccountController::class, 'create'])->name('customer-account.create');
    Route::get('/customer-account/edit/{id}', [CustomerAccountController::class, 'edit'])->name('customer-account.edit');
    Route::post('/customer-account/update', [CustomerAccountController::class, 'update'])->name('customer-account.update');
    Route::get('/customer-account/pdf/{id}', [CustomerAccountController::class, 'pdf'])->name('customer-account.pdf');
});

Route::get('work-order/pdf/{id}', [WorkOrderController::class, 'pdf'])->name('work-order.pdf');

require __DIR__ . '/auth.php';
