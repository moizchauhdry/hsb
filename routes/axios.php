<?php

use App\Http\Controllers\AxiosController;
use Illuminate\Support\Facades\Route;

Route::prefix('axios')->group(function () {
    Route::any('/fetch/cobs', [AxiosController::class, 'fetchCobs'])->name('axios.fetch.cobs');
    Route::any('/fetch/clients', [AxiosController::class, 'fetchClients'])->name('axios.fetch.clients');

    Route::any('/fetch/policy-types', [AxiosController::class, 'fetchPolicyTypes'])->name('axios.fetch.policy-types');
    Route::any('/fetch/agencies', [AxiosController::class, 'fetchAgencies'])->name('axios.fetch.agencies');
    Route::any('/fetch/insurers', [AxiosController::class, 'fetchInsurers'])->name('axios.fetch.insurers');

    Route::any('/fetch/clients/v2', [AxiosController::class, 'fetchClientsV2'])->name('axios.fetch.clients.v2');
    Route::any('/fetch/cobs/v2', [AxiosController::class, 'fetchCobsV2'])->name('axios.fetch.cobs.v2');
});
