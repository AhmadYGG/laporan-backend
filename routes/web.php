<?php

use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RecapController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Fullstack)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('landing');
})->name('home');

Route::get('/landing', [LandingController::class, 'index'])->name('landing');

Route::middleware(['guest'])->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'loginWeb'])->name('login.post');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/register', [AuthController::class, 'registerWeb'])->name('register.post');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logoutWeb'])->name('logout');
    Route::get('/me', [AuthController::class, 'me'])->name('profile');
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

        Route::put('/{id}/status', [ReportController::class, 'updateStatusController'])->name('update-status');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    // ==================
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
        Route::put('/{id}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::put('/read-all', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    });

    Route::middleware('admin')->prefix('recap')->name('recap.')->group(function () {
        Route::get('/', [RecapController::class, 'index'])->name('index');
        Route::get('/export', [RecapController::class, 'export'])->name('export');
    });

    Route::middleware('admin')->prefix('logs')->name('logs.')->group(function () {
        Route::get('/', [LogController::class, 'index'])->name('index');
    });
});
