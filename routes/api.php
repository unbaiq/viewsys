<?php

use App\Http\Controllers\Api\PlayerController;

Route::prefix('player')->group(function () {

    Route::post('/register', [PlayerController::class,'register']);
    Route::post('/login', [PlayerController::class,'login']);

    Route::get('/sync', [PlayerController::class,'sync']);
    Route::get('/schedule', [PlayerController::class,'schedule']);
    Route::get('/media', [PlayerController::class,'media']);

    Route::post('/heartbeat', [PlayerController::class,'heartbeat']);
    Route::post('/log', [PlayerController::class,'log']);

    Route::get('/config', [PlayerController::class,'config']);

});