<?php

use App\Http\Controllers\AxiosController;
use Illuminate\Support\Facades\Route;

Route::prefix('axios')->group(function () {
    Route::any('/fetch/cobs', [AxiosController::class, 'fetchCobs'])->name('axios.fetch.cobs');
    Route::any('/fetch/clients', [AxiosController::class, 'fetchClients'])->name('axios.fetch.clients');
});
