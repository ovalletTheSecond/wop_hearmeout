<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AdminController;


// Intro / landing page (SEO)
Route::get('/intro', function () { return view('intro'); })->name('intro');
// When user clicks continue we mark intro seen and redirect to the browsing homepage
Route::get('/intro/continue', function (\Illuminate\Http\Request $request) {
    $request->session()->put('intro_seen', true);
    return redirect()->route('home');
})->name('intro.continue');

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/reset-seen', [HomeController::class, 'resetSeen'])->name('reset.seen');

// Authenticated actions
Route::middleware('auth')->group(function () {
    Route::post('/vote', [HomeController::class, 'vote'])->name('vote.submit');
    Route::post('/report', [HomeController::class, 'report'])->name('report.submit');
});

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('login.google.callback');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Registration routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Account (requires authentication)
Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::post('/crush', [AccountController::class, 'store'])->name('crush.store');
    Route::put('/crush', [AccountController::class, 'update'])->name('crush.update');
    Route::delete('/crush', [AccountController::class, 'destroy'])->name('crush.destroy');
});

# Comments (requires authentication)
Route::middleware('auth')->group(function () {
    Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');
    Route::post('/comment/react', [CommentController::class, 'react'])->name('comment.react');
});

# Support page
Route::view('/support', 'support')->name('support');

# Contact
Route::view('/contact', 'contact')->name('contact');
Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

# Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::delete('/crush/{id}', [AdminController::class, 'deleteCrush'])->name('admin.crush.delete');
    Route::post('/crush/{id}/priority', [AdminController::class, 'togglePriority'])->name('admin.crush.priority');
    Route::post('/message/{id}/read', [AdminController::class, 'markMessageAsRead'])->name('admin.message.read');
    Route::post('/reset-seen', [AdminController::class, 'resetSeenCrushes'])->name('admin.reset.seen');
    
    // Gestion des catégories
    Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('admin.categories.delete');
});
