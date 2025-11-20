<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protected
Route::middleware(['auth:api'])->group(function () {
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);

    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'indexReportController']);
        Route::post('/', [ReportController::class, 'createReportController']);
        Route::put('/{id}', [ReportController::class, 'updateReportController']);
        Route::delete('/{id}', [ReportController::class, 'deleteReportController']);

        Route::get('/detail/{id}', [ReportController::class, 'showReportController']);
    });
});
