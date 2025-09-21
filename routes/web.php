<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportCategoryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReportStatusController;
use App\Http\Controllers\Admin\ResidentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ReportController as UserReportController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;


Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage Linked Succesfully.';
});

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/reports', [UserReportController::class, 'index'])->name('report.index');
Route::get('/report/{code}', [UserReportController::class, 'show'])->name('report.show');
// Route::get('/report/{code}/print', [UserReportController::class, 'print'])->name('report.print');

Route::middleware(['auth'])->group(function () {

    Route::get('/take-report', [UserReportController::class, 'take'])->name('report.take');
    Route::get('/preview', [UserReportController::class, 'preview'])->name('report.preview');
    Route::get('/create-report', [UserReportController::class, 'create'])->name('report.create');
    Route::post('/create-report', [UserReportController::class, 'store'])->name('report.store');
    Route::get('/report-success', [UserReportController::class, 'success'])->name('report.success');

    Route::get('/my-report', [UserReportController::class, 'myReport'])->name('report.myreport');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('/resident', ResidentController::class);
    Route::resource('/report-category', ReportCategoryController::class);
    Route::resource('/report', ReportController::class);

    Route::get('/report-status/{reportId}/create', [ReportStatusController::class, 'create'])->name('report-status.create');
    Route::get('/report/{id}/print', [ReportController::class, 'print'])->name('report.print');
    Route::resource('/report-status', ReportStatusController::class)->except('create');
});

// Add notification count routes
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications/count', [UserReportController::class, 'notificationCount'])->name('notifications.count');
    Route::get('/notifications/latest', [UserReportController::class, 'latestNotification'])->name('notifications.latest');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/notifications/count', [ReportController::class, 'notificationCount'])->name('notifications.count');
});

// Route media non-symlink untuk melayani file dari storage/app/public
Route::get('/media/{path}', function (string $path) {
    // Hindari path traversal
    if (str_contains($path, '..')) {
        abort(400);
    }

    $disk = Storage::disk('public');

    // Periksa keberadaan file menggunakan API Laravel yang benar
    if (!$disk->exists($path)) {
        abort(404);
    }

    // Stream respons langsung dari storage dan tambahkan header cache
    $response = $disk->response($path);
    $response->headers->set('Cache-Control', 'public, max-age=31536000');
    return $response;
})->where('path', '.*')->name('media');
