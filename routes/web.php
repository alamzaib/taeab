<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AgentAuthController;
use App\Http\Controllers\Auth\SeekerAuthController;
use App\Http\Controllers\Auth\CompanyAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Agent\DashboardController as AgentDashboardController;
use App\Http\Controllers\Agent\JobController as AgentJobController;
use App\Http\Controllers\Seeker\DashboardController as SeekerDashboardController;
use App\Http\Controllers\Company\DashboardController as CompanyDashboardController;
use App\Http\Controllers\Company\JobController as CompanyJobController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JobController as AdminJobController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\PageController;

// Home page
Route::get('/', function () {
    return view('home');
});

// Static Pages
Route::redirect('/pages', '/pages/about');
Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
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
        Route::resource('jobs', AgentJobController::class)->except(['show'])->names([
            'index' => 'agent.jobs.index',
            'create' => 'agent.jobs.create',
            'store' => 'agent.jobs.store',
            'edit' => 'agent.jobs.edit',
            'update' => 'agent.jobs.update',
            'destroy' => 'agent.jobs.destroy',
        ]);
    });
});

// Seeker Routes
Route::prefix('seeker')->group(function () {
    Route::get('/login', [SeekerAuthController::class, 'showLoginForm'])->name('seeker.login');
    Route::post('/login', [SeekerAuthController::class, 'login']);
    Route::get('/register', [SeekerAuthController::class, 'showRegisterForm'])->name('seeker.register');
    Route::post('/register', [SeekerAuthController::class, 'register']);
    Route::post('/logout', [SeekerAuthController::class, 'logout'])->name('seeker.logout');
    Route::get('/login/linkedin', [App\Http\Controllers\Auth\SeekerLinkedInController::class, 'redirect'])->name('seeker.login.linkedin');
    Route::get('/login/linkedin/callback', [App\Http\Controllers\Auth\SeekerLinkedInController::class, 'callback'])->name('seeker.login.linkedin.callback');
    
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
        Route::resource('jobs', CompanyJobController::class)->except(['show'])->names([
            'index' => 'company.jobs.index',
            'create' => 'company.jobs.create',
            'store' => 'company.jobs.store',
            'edit' => 'company.jobs.edit',
            'update' => 'company.jobs.update',
            'destroy' => 'company.jobs.destroy',
        ]);
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
        Route::resource('jobs', AdminJobController::class)->except(['show'])->names([
            'index' => 'admin.jobs.index',
            'create' => 'admin.jobs.create',
            'store' => 'admin.jobs.store',
            'edit' => 'admin.jobs.edit',
            'update' => 'admin.jobs.update',
            'destroy' => 'admin.jobs.destroy',
        ]);
        Route::get('jobs/import', [AdminJobController::class, 'importForm'])->name('admin.jobs.import');
        Route::post('jobs/import', [AdminJobController::class, 'importStore'])->name('admin.jobs.import.store');
        Route::resource('pages', AdminPageController::class)->except(['show'])->names([
            'index' => 'admin.pages.index',
            'create' => 'admin.pages.create',
            'store' => 'admin.pages.store',
            'edit' => 'admin.pages.edit',
            'update' => 'admin.pages.update',
            'destroy' => 'admin.pages.destroy',
        ]);
        
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
