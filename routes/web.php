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
use App\Http\Controllers\Admin\ClaimController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\EndorsementController;
use App\Http\Controllers\Admin\RenewalController;
use App\Http\Controllers\ExcelImportController;
use App\Http\Controllers\ReportController;

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

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->middleware(['auth', 'verified', 'permission:analytics'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('users')->group(function () {
        Route::any('/list/{slug}', [UserController::class, 'index'])->name('user.index')->middleware('permission:user_list');
        Route::post('/create', [UserController::class, 'create'])->name('user.create')->middleware('permission:user_create');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit')->middleware('permission:user_update');
        Route::post('/update', [UserController::class, 'update'])->name('user.update')->middleware('permission:user_update');

        Route::get('/selected-cob/{id}', [UserController::class, 'selectedCob'])->name('user.selected-cob')->middleware('permission:user_update');
        Route::post('/assign-cob', [UserController::class, 'assignCob'])->name('user.assign-cob')->middleware('permission:user_update');
        Route::get('/selected-client/{id}', [UserController::class, 'selectedClient'])->name('user.selected-client')->middleware('permission:user_update');
        Route::post('/assign-client', [UserController::class, 'assignClient'])->name('user.assign-client')->middleware('permission:user_update');
    });

    Route::prefix('clients')->group(function () {
        Route::any('/list', [ClientController::class, 'index'])->name('client.index')->middleware('permission:client_list');
        Route::post('/create', [ClientController::class, 'create'])->name('client.create')->middleware('permission:client_create');
        Route::get('/edit/{id}', [ClientController::class, 'edit'])->name('client.edit')->middleware('permission:client_update');
        Route::post('/update', [ClientController::class, 'update'])->name('client.update')->middleware('permission:client_update');
        Route::any('/groups/list', [ClientController::class, 'groupIndex'])->name('client.group.index')->middleware('permission:client_list');
    });

    Route::prefix('cobs')->group(function () {
        Route::any('/', [BusinessClassController::class, 'index'])->name('cob.index')->middleware('permission:cob_list');
        Route::get('/create', [BusinessClassController::class, 'create'])->name('cob.create')->middleware('permission:cob_create');
        Route::post('/store', [BusinessClassController::class, 'store'])->name('cob.store')->middleware('permission:cob_create');
        Route::get('/edit/{id}', [BusinessClassController::class, 'edit'])->name('cob.edit')->middleware('permission:cob_update');
        Route::post('/update', [BusinessClassController::class, 'update'])->name('cob.update')->middleware('permission:cob_update');
    });

    Route::prefix('insurers')->group(function () {
        Route::get('/', [InsuranceController::class, 'index'])->name('insurance.index')->middleware('permission:insurer_list');
    });

    Route::prefix('agencies')->group(function () {
        Route::get('/', [AgencyController::class, 'index'])->name('agency.index')->middleware('permission:agency_list');
    });

    Route::prefix('policy')->group(function () {
        Route::any('/', [PolicyController::class, 'index'])->name('policy.index')->middleware('permission:policy_list');
        Route::get('/create', [PolicyController::class, 'create'])->name('policy.create')->middleware('permission:policy_create');
        Route::post('/store', [PolicyController::class, 'store'])->name('policy.store')->middleware('permission:policy_create');
        Route::get('/edit/{id}', [PolicyController::class, 'edit'])->name('policy.edit')->middleware('permission:policy_update');
        Route::post('/update', [PolicyController::class, 'update'])->name('policy.update')->middleware('permission:policy_update');

        Route::get('/detail/{id}', [PolicyController::class, 'detail'])->name('policy.detail')->middleware('permission:policy_detail');
        Route::post('/detail-2', [PolicyController::class, 'detail2'])->name('policy.detail-2')->middleware('permission:policy_detail');

        Route::delete('/delete', [PolicyController::class, 'delete'])->name('policy.delete')->middleware('permission:policy_delete');
        Route::post('/additional-notes', [PolicyController::class, 'additionalNotes'])->name('policy.additionalNotes')->middleware('permission:policy_note');
        Route::post('/uploads', [PolicyController::class, 'uploads'])->name('policy.uploads')->middleware('permission:policy_upload');
        Route::get('/getDepartmentByBusinessClass/{id}', [PolicyController::class, 'getDepartmentByBusinessClass'])->name('policy.getDepartmentByBusinessClass');
        Route::get('/getBusinessClassByPercent/{id}', [PolicyController::class, 'getBusinessClassByPercent'])->name('policy.getBusinessClassByPercent');
        Route::post('/installment-plan', [PolicyController::class, 'installmentPlan'])->name('policy.installmentPlan');
        Route::post('/import', [PolicyController::class, 'importData'])->name('policy.import')->middleware('permission:excel_import');
        Route::delete('/uploads/destroy/{id}', [PolicyController::class, 'deleteUpload'])->name('policy.uploads.destroy');
        Route::delete('/notes/destroy/{id}', [PolicyController::class, 'deleteNote'])->name('policy.notes.destroy');
    });

    Route::prefix('claims')->group(function () {
        Route::any('/', [ClaimController::class, 'index'])->name('claim.index')->middleware('permission:claim_list');

        Route::post('/store', [ClaimController::class, 'store'])->name('claim.store')->middleware('permission:policy_claim');
        Route::post('/update', [ClaimController::class, 'update'])->name('claim.update')->middleware('permission:policy_claim');
        Route::get('/fetch/claim/{id}', [ClaimController::class, 'fetch'])->name('claim.fetch')->middleware('permission:policy_claim');

        Route::get('/fetch/claim-notes/{claim_id}/{policy_id}', [ClaimController::class, 'fetchClaimNotes'])->name('claim.fetch.claim-notes')->middleware('permission:policy_claim');
        Route::post('/store/claim-note', [ClaimController::class, 'storeClaimNote'])->name('claim.store.claim-note')->middleware('permission:policy_claim');

        Route::get('/fetch/claim-uploads/{claim_id}/{policy_id}', [ClaimController::class, 'fetchClaimUploads'])->name('claim.fetch.claim-uploads')->middleware('permission:policy_claim');
        Route::post('/store/claim-upload', [ClaimController::class, 'storeClaimUpload'])->name('claim.store.claim-upload')->middleware('permission:policy_claim');
        Route::delete('/uploads/destroy/{id}', [ClaimController::class, 'deleteClaimUpload'])->name('claim.uploads.destroy');
    });

    Route::prefix('renewals')->group(function () {
        Route::any('/', [RenewalController::class, 'index'])->name('renewal.index');
        Route::any('/clients', [RenewalController::class, 'clientList'])->name('renewal.client.index');
    });

    Route::prefix('endorsements')->group(function () {
        Route::any('/', [EndorsementController::class, 'index'])->name('endorsement.index');
        // ->middleware('permission:renewal_list');
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('role.index')->middleware('permission:role_list');
        Route::post('/create', [RoleController::class, 'create'])->name('role.create')->middleware('permission:role_create');
        Route::post('/update', [RoleController::class, 'update'])->name('role.update')->middleware('permission:role_update');
    });

    Route::prefix('reports')->group(function () {
        Route::any('/list/{slug}', [ReportController::class, 'index'])->name('report.index');
        Route::any('/export/{slug}', [ReportController::class, 'export'])->name('report.export');
    });

    Route::prefix('error-logs')->group(function () {
        Route::any('/list', [ExcelImportController::class, 'index'])->name('error-logs.index')->middleware('permission:excel_import');
    });
});


require __DIR__ . '/auth.php';
require __DIR__ . '/axios.php';
