<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('jwt.auth');
    Route::get('me', [AuthController::class, 'me'])->middleware('jwt.auth');
});
Route::middleware(['jwt.auth'])->group(function () {
    Route::prefix('announcements')->group(function () {
        Route::get('/', [AnnouncementController::class, 'index']);

        Route::get('/my-announcements', [AnnouncementController::class, 'myAnnouncements'])
            ->middleware('role:recruiter');

        Route::get('/{id}', [AnnouncementController::class, 'show']);

        Route::post('/', [AnnouncementController::class, 'store'])
            ->middleware(['jwt.auth','role:candidate']);

        Route::put('/{id}', [AnnouncementController::class, 'update'])
            ->middleware('role:recruiter');

        Route::delete('/{id}', [AnnouncementController::class, 'destroy'])
            ->middleware('role:recruiter');
    });
    Route::prefix('utilisateurs')->group(function () {
        Route::get('/profile', [AnnouncementController::class, 'index']);

        Route::put('/my-profile', [AnnouncementController::class, 'show'])
            ->middleware('role:recruiter');

        Route::delete('/utilisateurs/{id}', [AnnouncementController::class, 'delete'])
        ->middleware("role:admin");
    });
    Route::prefix('candidatures')->group(function () {
        Route::get('/miennes', [ApplicationController::class, 'index'])
            ->middleware('role:candidate');


        Route::post('/', [ApplicationController::class, 'show'])
            ->middleware('role:recruiter');

        Route::delete('/{id}', [ApplicationController::class, 'delete'])
            ->middleware("role:admin");
    });

});


