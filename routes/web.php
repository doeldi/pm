<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResponseController;

Route::middleware(['isAnonymous'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');

    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware(['isLogin'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/home', function () {
        return view('home');
    })->name('home');

    Route::prefix('/reports')->name('report.')->group(function () {
        Route::get('/article', [ReportController::class, 'index'])->name('data-report');
        Route::get('/create', [ReportController::class, 'create'])->name('create');
        Route::post('/store', [ReportController::class, 'store'])->name('store');
        // Route::get('/{report}/edit', [ReportController::class, 'edit'])->name('edit');
        // Route::put('/{report}', [ReportController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [ReportController::class, 'destroy'])->name('destroy');
        // Route::post('/{report}/response', [ReportController::class, 'response'])->name('response');

        Route::post('/report/{id}/vote', [ReportController::class, 'vote'])->name('vote');
        Route::post('/report/{id}/unvote', [ReportController::class, 'unvote'])->name('unvote');

        // Route untuk detail laporan
        Route::get('/report/{id}', [ReportController::class, 'show'])->name('show');

        // Route untuk menyimpan komentar
        Route::post('/reports/{id}/comments', [ReportController::class, 'storeComment'])->name('storeComment');

        // routes/web.php
        Route::get('/report//me', [ReportController::class, 'myReports'])->name('myReports');
    });

    Route::middleware('isStaff')->group(function () {
        Route::prefix('/responses')->name('responses.')->group(function () {
            Route::get('/responses', [ResponseController::class, 'index'])->name('index');
            // Route::get('/responses/create', [ResponseController::class, 'create'])->name('responses.create');
            Route::post('/response/store/{id}', [ResponseController::class, 'store'])->name('store');
            Route::get('/responses/{id}', [ResponseController::class, 'show'])->name('show');
            Route::post('/responses/{id}/progress', [ResponseController::class, 'storeProgress'])->name('storeProgress');
            Route::get('/responses/{id}/edit', [ResponseController::class, 'edit'])->name('edit');
            Route::patch('/responses/{id}/update', [ResponseController::class, 'update'])->name('update');
            Route::get('/reports/export', [ReportController::class, 'export'])->name('export');
        });
    });

    Route::middleware('isHeadStaff')->group(function () {
        Route::prefix('/headstaff')->name('staff.')->group(function () {
            Route::get('/data', [UserController::class, 'index'])->name('index');
            Route::post('/store', [UserController::class, 'store'])->name('store');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
            Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('resetPassword');
        });
    });

});
