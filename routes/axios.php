<?php

use App\Http\Controllers\AxiosController;
use Illuminate\Support\Facades\Route;

Route::prefix('axios')->group(function () {
    Route::get('/fetch/cobs', [AxiosController::class, 'fetchCobs'])->name('axios.fetch.cobs');
});
