<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\CommentController;

Route::middleware(['isAnonymous'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');

    Route::get('/auth', [AuthController::class, 'showLoginRegisterForm'])->name('auth.login.register.form');
    Route::post('/auth', [AuthController::class, 'loginRegister'])->name('auth.login.register');
});

Route::middleware(['isLogin'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('isGuest')->group(function () {
        Route::prefix('/reports')->name('report.')->group(function () {
            Route::get('/article', [ReportController::class, 'index'])->name('data-report');
            Route::get('/create', [ReportController::class, 'create'])->name('create');
            Route::post('/store', [ReportController::class, 'store'])->name('store');
            Route::get('/report/{id}', [ReportController::class, 'show'])->name('show');
            Route::delete('delete/{id}', [ReportController::class, 'destroy'])->name('destroy');
            Route::get('/report//me', [ReportController::class, 'myReports'])->name('myReports');
            Route::post('/reports/{id}/toggle-vote', [ReportController::class, 'toggleVote'])->name('toggleVote');

            Route::post('/reports/{id}/comments', [CommentController::class, 'store'])->name('storeComment');
            Route::delete('reports/comments/{commentId}/delete', [CommentController::class, 'destroy'])->name('deleteComment');
            Route::patch('/reports/comments/{commentId}/update', [CommentController::class, 'update'])->name('updateComment');
        });
    });

    Route::middleware('isStaff')->group(function () {
        Route::prefix('/responses')->name('responses.')->group(function () {
            Route::get('/responses', [ResponseController::class, 'index'])->name('index');
            Route::post('/response/store/{id}', [ResponseController::class, 'store'])->name('store');
            Route::get('/responses/{id}', [ResponseController::class, 'show'])->name('show');
            Route::post('/responses/{id}/progress', [ResponseController::class, 'storeProgress'])->name('storeProgress');
            Route::patch('/responses/{id}/update', [ResponseController::class, 'update'])->name('update');
            Route::delete('/responses/{id}/delete', [ResponseController::class, 'destroy'])->name('destroy');

            Route::get('/reports/export', [ReportController::class, 'export'])->name('export');
        });
    });

    Route::middleware('isHeadStaff')->group(function () {
        Route::prefix('/headstaff')->name('staff.')->group(function () {
            Route::get('/data', [UserController::class, 'index'])->name('index');
            Route::get('/create/data', [UserController::class, 'create'])->name('create');
            Route::post('/store', [UserController::class, 'store'])->name('store');
            Route::delete('/{user}/delete', [UserController::class, 'destroy'])->name('destroy');
            Route::post('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('resetPassword');
        });
    });
});
