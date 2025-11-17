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
        
        // User Management Routes
        Route::prefix('users')->name('admin.users.')->group(function () {
            // Seekers
            Route::get('/seekers', [App\Http\Controllers\Admin\UserController::class, 'seekersIndex'])->name('seekers.index');
            Route::get('/seekers/create', [App\Http\Controllers\Admin\UserController::class, 'seekersCreate'])->name('seekers.create');
            Route::post('/seekers', [App\Http\Controllers\Admin\UserController::class, 'seekersStore'])->name('seekers.store');
            Route::get('/seekers/{seeker}/edit', [App\Http\Controllers\Admin\UserController::class, 'seekersEdit'])->name('seekers.edit');
            Route::put('/seekers/{seeker}', [App\Http\Controllers\Admin\UserController::class, 'seekersUpdate'])->name('seekers.update');
            Route::delete('/seekers/{seeker}', [App\Http\Controllers\Admin\UserController::class, 'seekersDestroy'])->name('seekers.destroy');
            
            // Agents
            Route::get('/agents', [App\Http\Controllers\Admin\UserController::class, 'agentsIndex'])->name('agents.index');
            Route::get('/agents/create', [App\Http\Controllers\Admin\UserController::class, 'agentsCreate'])->name('agents.create');
            Route::post('/agents', [App\Http\Controllers\Admin\UserController::class, 'agentsStore'])->name('agents.store');
            Route::get('/agents/{agent}/edit', [App\Http\Controllers\Admin\UserController::class, 'agentsEdit'])->name('agents.edit');
            Route::put('/agents/{agent}', [App\Http\Controllers\Admin\UserController::class, 'agentsUpdate'])->name('agents.update');
            Route::delete('/agents/{agent}', [App\Http\Controllers\Admin\UserController::class, 'agentsDestroy'])->name('agents.destroy');
            
            // Companies
            Route::get('/companies', [App\Http\Controllers\Admin\UserController::class, 'companiesIndex'])->name('companies.index');
            Route::get('/companies/create', [App\Http\Controllers\Admin\UserController::class, 'companiesCreate'])->name('companies.create');
            Route::post('/companies', [App\Http\Controllers\Admin\UserController::class, 'companiesStore'])->name('companies.store');
            Route::get('/companies/{company}/edit', [App\Http\Controllers\Admin\UserController::class, 'companiesEdit'])->name('companies.edit');
            Route::put('/companies/{company}', [App\Http\Controllers\Admin\UserController::class, 'companiesUpdate'])->name('companies.update');
            Route::delete('/companies/{company}', [App\Http\Controllers\Admin\UserController::class, 'companiesDestroy'])->name('companies.destroy');
        });
    });
});
