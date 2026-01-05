<?php

use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RecapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Fullstack)
|--------------------------------------------------------------------------
*/

// ========================================
// Public Routes (Guest Only)
// ========================================
Route::middleware(['guest'])->group(function () {
    // Auth Pages
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'loginWeb'])->name('login.post');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/register', [AuthController::class, 'registerWeb'])->name('register.post');
});

// ========================================
// Protected Routes (Authenticated Users)
// ========================================
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logoutWeb'])->name('logout');

    // Profile
    Route::get('/me', [AuthController::class, 'me'])->name('profile');

    // ==================
    // Reports
    // ==================
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'indexReportController'])->name('index');
        Route::get('/create', function () {
            return view('reports.create');
        })->name('create');
        Route::post('/', [ReportController::class, 'createReportController'])->name('store');
        Route::get('/{id}', [ReportController::class, 'showReportController'])->name('show');
        Route::get('/{id}/edit', function ($id) {
            return view('reports.edit', compact('id'));
        })->name('edit');
        Route::put('/{id}', [ReportController::class, 'updateReportController'])->name('update');
        Route::delete('/{id}', [ReportController::class, 'deleteReportController'])->name('destroy');

        // Admin only: Update report status
        Route::put('/{id}/status', [ReportController::class, 'updateStatusController'])->name('update-status');
    });

    // ==================
    // Users (Admin)
    // ==================
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    // ==================
    // Notifications
    // ==================
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
        Route::put('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::put('/read-all', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    });

    // ==================
    // Recap (Admin Only)
    // ==================
    Route::middleware('admin')->prefix('recap')->name('recap.')->group(function () {
        Route::get('/', [RecapController::class, 'index'])->name('index');
        Route::get('/export', [RecapController::class, 'export'])->name('export');
    });
});
