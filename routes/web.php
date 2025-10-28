<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CommentController;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/vote', [HomeController::class, 'vote'])->name('vote.submit');
Route::post('/report', [HomeController::class, 'report'])->name('report.submit');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('login.google.callback');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Account (requires authentication)
Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::post('/crush', [AccountController::class, 'store'])->name('crush.store');
    Route::put('/crush', [AccountController::class, 'update'])->name('crush.update');
    Route::delete('/crush', [AccountController::class, 'destroy'])->name('crush.destroy');
});

// Comments (requires authentication)
Route::middleware('auth')->group(function () {
    Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');
    Route::post('/comment/react', [CommentController::class, 'react'])->name('comment.react');
});
