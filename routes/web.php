<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AgentAuthController;
use App\Http\Controllers\Auth\SeekerAuthController;
use App\Http\Controllers\Auth\CompanyAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Agent\DashboardController as AgentDashboardController;
use App\Http\Controllers\Seeker\DashboardController as SeekerDashboardController;
use App\Http\Controllers\Company\DashboardController as CompanyDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

// Home page
Route::get('/', function () {
    return view('home');
});

// Agent Routes
Route::prefix('agent')->group(function () {
    Route::get('/login', [AgentAuthController::class, 'showLoginForm'])->name('agent.login');
    Route::post('/login', [AgentAuthController::class, 'login']);
    Route::get('/register', [AgentAuthController::class, 'showRegisterForm'])->name('agent.register');
    Route::post('/register', [AgentAuthController::class, 'register']);
    Route::post('/logout', [AgentAuthController::class, 'logout'])->name('agent.logout');
    
    Route::middleware('auth:agent')->group(function () {
        Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('agent.dashboard');
    });
});

// Seeker Routes
Route::prefix('seeker')->group(function () {
    Route::get('/login', [SeekerAuthController::class, 'showLoginForm'])->name('seeker.login');
    Route::post('/login', [SeekerAuthController::class, 'login']);
    Route::get('/register', [SeekerAuthController::class, 'showRegisterForm'])->name('seeker.register');
    Route::post('/register', [SeekerAuthController::class, 'register']);
    Route::post('/logout', [SeekerAuthController::class, 'logout'])->name('seeker.logout');
    
    Route::middleware('auth:seeker')->group(function () {
        Route::get('/dashboard', [SeekerDashboardController::class, 'index'])->name('seeker.dashboard');
    });
});

// Company Routes
Route::prefix('company')->group(function () {
    Route::get('/login', [CompanyAuthController::class, 'showLoginForm'])->name('company.login');
    Route::post('/login', [CompanyAuthController::class, 'login']);
    Route::get('/register', [CompanyAuthController::class, 'showRegisterForm'])->name('company.register');
    Route::post('/register', [CompanyAuthController::class, 'register']);
    Route::post('/logout', [CompanyAuthController::class, 'logout'])->name('company.logout');
    
    Route::middleware('auth:company')->group(function () {
        Route::get('/dashboard', [CompanyDashboardController::class, 'index'])->name('company.dashboard');
    });
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
    
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('admin.settings.index');
        Route::post('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('admin.settings.update');
    });
});
