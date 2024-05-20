<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AgencyController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\InsuranceController;
use App\Http\Controllers\CustomerAccountController;
use App\Http\Controllers\Admin\ClassOfBusinessController;

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

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index')->middleware('permission:user-list');
        Route::post('/create', [UserController::class, 'create'])->name('user.create')->middleware('permission:user-create');
        Route::post('/update', [UserController::class, 'update'])->name('user.update')->middleware('permission:user-update');
    });

    Route::prefix('class-of-business')->group(function () {
        Route::get('/', [ClassOfBusinessController::class, 'index'])->name('class-of-business.index')->middleware('permission:user-list');
        Route::post('/create', [ClassOfBusinessController::class, 'create'])->name('class-of-business.create')->middleware('permission:user-create');
        Route::post('/update', [ClassOfBusinessController::class, 'update'])->name('class-of-business.update')->middleware('permission:user-update');
    });

    Route::prefix('insurance')->group(function () {
        Route::get('/', [InsuranceController::class, 'index'])->name('insurance.index')->middleware('permission:user-list');
    });

    Route::prefix('agency')->group(function () {
        Route::get('/', [AgencyController::class, 'index'])->name('agency.index')->middleware('permission:user-list');
    });

    Route::prefix('policy')->group(function () {
        Route::get('/', [PolicyController::class, 'index'])->name('policy.index')->middleware('permission:user-list');
        Route::get('/create', [PolicyController::class, 'create'])->name('policy.create');
        Route::post('/store', [PolicyController::class, 'store'])->name('policy.store');
        Route::get('/edit', [PolicyController::class, 'edit'])->name('policy.edit');
        Route::post('/update', [PolicyController::class, 'update'])->name('policy.update');
        Route::get('/detail/{id}', [PolicyController::class, 'detail'])->name('policy.detail');
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('role.index')->middleware('permission:role-list');
        Route::post('/create', [RoleController::class, 'create'])->name('role.create')->middleware('permission:role-create');
        Route::post('/update', [RoleController::class, 'update'])->name('role.update')->middleware('permission:role-update');
    });
});

require __DIR__ . '/auth.php';
