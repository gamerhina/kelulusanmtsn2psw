<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;

Route::get('/', [StudentController::class, 'index'])->name('student.index');
Route::post('/cek-kelulusan', [StudentController::class, 'check'])->name('student.check');

// Global login route for auth middleware
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'loginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login']);
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'nocache'])->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::post('/store', [AdminController::class, 'store'])->name('store');
        Route::put('/{id}', [AdminController::class, 'update'])->name('update');
        Route::post('/import', [AdminController::class, 'import'])->name('import');
        Route::get('/export-template', [AdminController::class, 'exportTemplate'])->name('export-template');
        Route::delete('/{id}', [AdminController::class, 'destroy'])->name('destroy');
        Route::post('/delete-all', [AdminController::class, 'deleteAll'])->name('delete-all');
        Route::post('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    });
});
