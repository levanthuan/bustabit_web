<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CaseGameController;
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

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware('guest')->group(function (): void {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:6,1');
});
