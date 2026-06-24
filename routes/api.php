<?php

use App\Http\Controllers\Api\PlayerController;
use Illuminate\Support\Facades\Route;

Route::prefix('player')->group(function () {

    Route::post('/login', [PlayerController::class,'login']);

    Route::get('/sync', [PlayerController::class,'sync']);

    // ✅ SINGLE CONTENT API (REPLACES schedule + media)
     Route::get('/schedule', [PlayerController::class,'schedule']);
    Route::get('/media', [PlayerController::class,'media']);

    Route::post('/screenshot', [PlayerController::class,'screenshot']);

    Route::post('/heartbeat', [PlayerController::class,'heartbeat']);
    Route::post('/log', [PlayerController::class,'log']);

    Route::get('/config', [PlayerController::class,'config']);
});