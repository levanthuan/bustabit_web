<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CaseGameController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function (): void {
    Route::get('/', function () {
        return view('home');
    })->name('home');

    Route::get('/tt/{game}/records/since', [CaseGameController::class, 'recordsSince'])
        ->where('game', 'tt3|tt5|tt7|tt10')
        ->name('case.records.since');

    Route::get('/tt/{game}', [CaseGameController::class, 'show'])
        ->where('game', 'tt3|tt5|tt7|tt10')
        ->name('case.show');

    Route::get('/tt/{game}/export-pdf', [CaseGameController::class, 'exportPdf'])
        ->where('game', 'tt3|tt5|tt7|tt10')
        ->name('case.export.pdf');

    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware('guest')->group(function (): void {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:6,1');
});
