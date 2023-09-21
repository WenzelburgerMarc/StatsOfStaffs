<?php

use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TimeTrackerController;
use App\Http\Controllers\UserController;
use App\Notifications\MyResetPasswordNotification;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/run-scheduler', function (Request $request) {

    $username = $request->input('username');
    $password = $request->input('password');

    $envUsername = env('SCHEDULER_USERNAME');
    $envPassword = env('SCHEDULER_PASSWORD');

    if ($username === $envUsername && $password === $envPassword) {
        Artisan::call('schedule:run');
        return 'Scheduler executed';
    }

    return response('Unauthorized', 401);
});

// Authenticated routes
Route::middleware(['forget-session'])->group(function () {

    Route::middleware('auth')->group(function () {
        Route::get('/', [SessionController::class, 'index'])->name('homepage');
        Route::post('logout', [SessionController::class, 'destroy'])->name('logout');

        // Staff Routes
        Route::get('/files/{category}/{filename}', [FileController::class, 'getFile'])->name('get-file');

        Route::prefix('staff')->group(function () {
            Route::get('edit', [UserController::class, 'edit'])->name('edit-user');
            Route::get('download-user-csv', [ExportController::class, 'downloadAuthedUserData'])->name('download-user-csv');
            Route::patch('edit', [UserController::class, 'update'])->name('update-user');
            Route::post('edit/avatar', [UserController::class, 'destroyAvatar'])->name('delete-avatar');
            Route::get('absence', [AbsenceController::class, 'index'])->name('manage-absences');
            Route::get('absence/download-user-absences-csv', [ExportController::class, 'downloadAuthedAbsencesData'])->name('download-user-absences-csv');
            Route::get('absence/request', [AbsenceController::class, 'create'])->name('create-absence');
            Route::post('absence/request', [AbsenceController::class, 'store'])->name('store-absence');
            Route::get('time-tracking', [TimeTrackerController::class, 'index'])->name('time-tracking');
        });

        // Chat Routes
        Route::prefix('chats')->group(function () {
            Route::get('/', [ChatController::class, 'index'])->name('chats');
            Route::get('create', [ChatController::class, 'create'])->name('create-chat');
            //Route::post('/chats/create', [ChatController::class, 'store'])->name('store-chat');
            Route::get('{username}', [ChatController::class, 'show'])->name('show-chat');
        });

        // Test Mails Routes
        Route::get('/preview-pw-forget-email', function () {
            return (new MyResetPasswordNotification('token'))->toMail(auth()->user())->render();
        });

    });

    // Guest routes
    Route::middleware('guest')->group(function () {
        Route::get('login', [SessionController::class, 'create'])->name('login');
        Route::post('sessions', [SessionController::class, 'store']);

        // Passwort-Reset-Links-Anfrage-Routen...
        Route::get('password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

        // Passwort-Reset-Routen...
        Route::get('password/reset/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('password/reset', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

    });

    // Admin Routes
    Route::middleware('can:admin')->group(function () {


        Route::prefix('admin/manage-employees')->group(function () {
            Route::get('/send-broadcast-mail', [MailController::class, 'create'])->name('send-broadcast-mail');
            Route::get('/', [EmployeeController::class, 'index'])->name('manage-employees');
            Route::get('/download-users-csv', [ExportController::class, 'downloadAllUserData'])->name('download-staffs-csv');
            Route::get('/download-absences-csv', [ExportController::class, 'downloadAllAbsencesData'])->name('download-absences-csv');
            Route::get('create', [EmployeeController::class, 'create'])->name('create-employee');
            Route::post('/', [EmployeeController::class, 'store'])->name('store-employee');
            Route::get('{username}/profile', [EmployeeController::class, 'edit'])->name('manage-employee');
            Route::get('{username}/profile/download-user-csv', [ExportController::class, 'downloadUserData'])->name('download-staff-csv');
            Route::patch('{username}/profile', [EmployeeController::class, 'update'])->name('update-employee');
            Route::post('{username}/profile', [EmployeeController::class, 'destroy'])->name('delete-employee');
            Route::post('{username}/profile/avatar', [EmployeeController::class, 'destroyAvatar'])->name('delete-employee-avatar');
            Route::get('{username}/time-tracking', [TimeTrackerController::class, 'index'])->name('employee-time-tracking');
            Route::get('{username}/absences', [EmployeeController::class, 'edit'])->name('employee-absences');
            Route::get('{username}/absence/create', [AbsenceController::class, 'create'])->name('create-employee-absence');
            Route::post('{username}/absence/create', [AbsenceController::class, 'store'])->name('store-employee-absence');
            Route::get('{username}/absences/export-user-absences-csv', [ExportController::class, 'downloadAbsencesForUser'])->name('download-employee-absences-csv');

        });
    });
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
