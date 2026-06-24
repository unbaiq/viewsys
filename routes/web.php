<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\SystemLogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ScreenController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClusterController;



Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {

    Route::resource('companies', CompanyController::class);
    Route::patch('/companies/{company}/toggle', [CompanyController::class, 'toggle'])
    ->name('companies.toggle');
    Route::resource('plans', PlanController::class);
    Route::post('/plans/{plan}/toggle', [PlanController::class, 'toggle'])
        ->name('plans.toggle');
    Route::resource('storage-usage', StorageController::class);
    // system log
    Route::get('/logs', [SystemLogController::class, 'index'])->name('logs.index');

    Route::get('/logs/{log}', [SystemLogController::class, 'show'])->name('logs.show');

    Route::delete('/logs/{log}', [SystemLogController::class, 'destroy'])->name('logs.destroy');

    Route::post('/logs/clear', [SystemLogController::class, 'clear'])->name('logs.clear');

    // user 
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
    Route::resource('screens', ScreenController::class);
    Route::post('/screens/{screen}/restart', [ScreenController::class, 'restart'])
    ->name('screens.restart');
    Route::post('/screens/{screen}/screenshot', [ScreenController::class, 'requestScreenshot'])->name('screens.screenshot');
    Route::get('/screens-map', [ScreenController::class, 'map'])->name('screens.map');
    Route::resource('clusters', ClusterController::class);
    Route::get('/get-screens/{company}', [ClusterController::class, 'getScreens']);
    Route::resource('media', MediaController::class)
    ->parameters(['media' => 'media']);


    Route::resource('playlists', PlaylistController::class);

    Route::post('/playlists/{playlist}/add-media', [PlaylistController::class, 'addMedia'])
        ->name('playlists.addMedia');

    Route::delete('/playlist-items/{item}', [PlaylistController::class, 'removeMedia'])
        ->name('playlist-items.destroy');
    Route::get('/get-screens/{companyId}', function ($companyId) {
        return \App\Models\Screen::where('company_id', $companyId)->get();
    });

    Route::resource('schedules', ScheduleController::class);
    Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
    Route::get('/devices/{screen}', [DeviceController::class, 'show'])->name('devices.show');
    Route::post('/devices/{screen}/restart', [DeviceController::class, 'restart'])->name('devices.restart');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read/{id}', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.readAll');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.delete');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');



});
Route::get('/create-storage-link', function () {
    $target = storage_path('app/public');
    $link = public_path('storage');
    if (!file_exists($link)) {
        if (!is_link($link)) {
            if (!file_exists($target)) {
                mkdir($target, 0777, true);
            }
            symlink($target, $link);
        }
    }
    return 'The [public/storage] directory has been linked.';
});

require __DIR__ . '/auth.php';
