<?php

use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Protected
Route::middleware(['jwt:auth'])->group(function () {
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);

    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'indexReportController']);
        Route::post('/', [ReportController::class, 'createReportController']);
        Route::put('/{id}', [ReportController::class, 'updateReportController']);
        Route::delete('/{id}', [ReportController::class, 'deleteReportController']);

        Route::get('/detail/{id}', [ReportController::class, 'showReportController']);
        
        // Admin only: Update report status
        Route::put('/{id}/status', [ReportController::class, 'updateStatusController']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
        Route::put('/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::put('/read-all', [NotificationController::class, 'markAllAsRead']);
    });
});*/
