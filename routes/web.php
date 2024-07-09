<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AgencyController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\InsuranceController;
use App\Http\Controllers\Admin\BusinessClassController;

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
});

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('users')->group(function () {
        Route::any('/', [UserController::class, 'index'])->name('user.index')->middleware('permission:user-list');
        Route::post('/create', [UserController::class, 'create'])->name('user.create')->middleware('permission:user-create');
        Route::post('/update', [UserController::class, 'update'])->name('user.update')->middleware('permission:user-update');
    });

    Route::prefix('business-class')->group(function () {
        Route::any('/', [BusinessClassController::class, 'index'])->name('businessClass.index')->middleware('permission:user-list');
        Route::get('/create', [BusinessClassController::class, 'create'])->name('businessClass.create');
        Route::post('/store', [BusinessClassController::class, 'store'])->name('businessClass.store');
        Route::get('/edit/{id}', [BusinessClassController::class, 'edit'])->name('businessClass.edit');
        Route::post('/update', [BusinessClassController::class, 'update'])->name('businessClass.update');
    });

    Route::prefix('insurance')->group(function () {
        Route::get('/', [InsuranceController::class, 'index'])->name('insurance.index')->middleware('permission:user-list');
    });

    Route::prefix('agency')->group(function () {
        Route::get('/', [AgencyController::class, 'index'])->name('agency.index')->middleware('permission:user-list');
    });

    Route::prefix('policy')->group(function () {
        Route::any('/', [PolicyController::class, 'index'])->name('policy.index')->middleware('permission:user-list');
        Route::get('/create', [PolicyController::class, 'create'])->name('policy.create');
        Route::post('/store', [PolicyController::class, 'store'])->name('policy.store');
        Route::get('/edit/{id}', [PolicyController::class, 'edit'])->name('policy.edit');
        Route::post('/update', [PolicyController::class, 'update'])->name('policy.update');
        Route::get('/detail/{id}', [PolicyController::class, 'detail'])->name('policy.detail');
        Route::delete('/delete', [PolicyController::class, 'delete'])->name('policy.delete');
        Route::post('/additional-notes', [PolicyController::class, 'additionalNotes'])->name('policy.additionalNotes');
        Route::post('/uploads', [PolicyController::class, 'uploads'])->name('policy.uploads');
        Route::get('/get/claim/{id}', [PolicyController::class, 'getClaim'])->name('policy.getClaim');
        Route::post('/claims', [PolicyController::class, 'claims'])->name('policy.claims');
        Route::post('/claim/update', [PolicyController::class, 'updateClaim'])->name('policy.updateClaim');
        Route::get('/get/claim/upload/{id}', [PolicyController::class, 'getClaimUpload'])->name('policy.getClaimUpload');
        Route::post('/claim/upload', [PolicyController::class, 'claimUpload'])->name('policy.claimUpload');
        Route::post('/claim/note', [PolicyController::class, 'claimNote'])->name('policy.claimNote');
        Route::get('/get/claim/note/{id}', [PolicyController::class, 'getClaimNote'])->name('policy.getClaimNote');
        Route::get('/getDepartmentByBusinessClass/{id}',[PolicyController::class,'getDepartmentByBusinessClass'])->name('policy.getDepartmentByBusinessClass');
        Route::get('/getBusinessClassByPercent/{id}',[PolicyController::class,'getBusinessClassByPercent'])->name('policy.getBusinessClassByPercent');
        Route::post('/installment-plan',[PolicyController::class,'installmentPlan'])->name('policy.installmentPlan');
        Route::post('/import',[PolicyController::class,'importData'])->name('policy.import');
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('role.index')->middleware('permission:role-list');
        Route::post('/create', [RoleController::class, 'create'])->name('role.create')->middleware('permission:role-create');
        Route::post('/update', [RoleController::class, 'update'])->name('role.update')->middleware('permission:role-update');
    });
});

require __DIR__ . '/auth.php';
