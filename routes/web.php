<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AgentAuthController;
use App\Http\Controllers\Auth\SeekerAuthController;
use App\Http\Controllers\Auth\CompanyAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Agent\DashboardController as AgentDashboardController;
use App\Http\Controllers\Agent\JobController as AgentJobController;
use App\Http\Controllers\Seeker\DashboardController as SeekerDashboardController;
use App\Http\Controllers\Seeker\ProfileController as SeekerProfileController;
use App\Http\Controllers\Seeker\ApplicationController as SeekerApplicationController;
use App\Http\Controllers\Company\DashboardController as CompanyDashboardController;
use App\Http\Controllers\Company\JobController as CompanyJobController;
use App\Http\Controllers\Company\ProfileController as CompanyProfileController;
use App\Http\Controllers\Company\ApplicationController as CompanyApplicationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JobController as AdminJobController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobListingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Seeker\DocumentController;
use App\Http\Controllers\Agent\ProfileController as AgentProfileController;
use App\Http\Controllers\Seeker\ResumeController as SeekerResumeController;
use App\Http\Controllers\CompanyListingController;
use App\Http\Controllers\PublicSeekerController;

// Home page
Route::get('/', function () {
    return view('home');
});

Route::get('/jobs', [JobListingController::class, 'index'])->name('jobs.index');
Route::get('/companies', [CompanyListingController::class, 'index'])->name('companies.index');
Route::get('/companies/{company}', [CompanyListingController::class, 'show'])->name('companies.show');
Route::get('/companies/{company}/reviews', [App\Http\Controllers\CompanyReviewController::class, 'getAllReviews'])->name('companies.reviews.all');
Route::post('/companies/{company}/reviews', [App\Http\Controllers\CompanyReviewController::class, 'store'])->middleware('auth:seeker')->name('companies.reviews.store');
Route::get('/reviews/{review}/edit', [App\Http\Controllers\CompanyReviewController::class, 'edit'])->middleware('auth:seeker')->name('companies.reviews.edit');
Route::put('/reviews/{review}', [App\Http\Controllers\CompanyReviewController::class, 'update'])->middleware('auth:seeker')->name('companies.reviews.update');
Route::post('/reviews/{review}/reply', [App\Http\Controllers\CompanyReviewController::class, 'reply'])->middleware('auth:company')->name('companies.reviews.reply');

// Static Pages
Route::redirect('/pages', '/pages/about');
Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');
Route::get('/talent/{code}', [PublicSeekerController::class, 'show'])->name('seekers.public.show');

Route::get('/jobs/{slug}', [JobListingController::class, 'show'])->name('jobs.show');
Route::get('/jobs/{slug}/apply', [JobApplicationController::class, 'create'])->middleware('auth:seeker')->name('jobs.apply.form');
Route::post('/jobs/{slug}/apply', [JobApplicationController::class, 'store'])->middleware('auth:seeker')->name('jobs.apply');
Route::post('/jobs/{slug}/favorite', [JobListingController::class, 'toggleFavorite'])->middleware('auth:seeker')->name('jobs.favorite');
Route::get('/contact', [App\Http\Controllers\ContactController::class, 'index'])->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');
Route::get('/login', fn () => redirect()->route('seeker.login'))->name('login');

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
        Route::get('/profile/edit', [AgentProfileController::class, 'edit'])->name('agent.profile.edit');
        Route::post('/profile', [AgentProfileController::class, 'update'])->name('agent.profile.update');
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
    Route::view('/password/reset', 'auth.seeker.reset-password')->name('seeker.password.reset');

    Route::middleware('auth:seeker')->group(function () {
        Route::get('/dashboard', [SeekerDashboardController::class, 'index'])->name('seeker.dashboard');
        Route::get('/profile/edit', [SeekerProfileController::class, 'edit'])->name('seeker.profile.edit');
        Route::post('/profile', [SeekerProfileController::class, 'update'])->name('seeker.profile.update');
        Route::post('/profile/refresh', [SeekerProfileController::class, 'refresh'])->name('seeker.profile.refresh');
        Route::prefix('resume')->name('seeker.resume.')->group(function () {
            Route::post('/profile', [SeekerResumeController::class, 'updateProfile'])->name('profile');
            Route::get('/preview', [SeekerResumeController::class, 'preview'])->name('preview');
            Route::post('/educations', [SeekerResumeController::class, 'storeEducation'])->name('educations.store');
            Route::delete('/educations/{education}', [SeekerResumeController::class, 'destroyEducation'])->name('educations.destroy');
            Route::post('/experiences', [SeekerResumeController::class, 'storeExperience'])->name('experiences.store');
            Route::delete('/experiences/{experience}', [SeekerResumeController::class, 'destroyExperience'])->name('experiences.destroy');
            Route::post('/references', [SeekerResumeController::class, 'storeReference'])->name('references.store');
            Route::delete('/references/{reference}', [SeekerResumeController::class, 'destroyReference'])->name('references.destroy');
            Route::post('/hobbies', [SeekerResumeController::class, 'storeHobby'])->name('hobbies.store');
            Route::delete('/hobbies/{hobby}', [SeekerResumeController::class, 'destroyHobby'])->name('hobbies.destroy');
        });
        Route::get('/applications', [SeekerApplicationController::class, 'index'])->name('seeker.applications.index');
        Route::get('/applications/{application}', [SeekerApplicationController::class, 'show'])->name('seeker.applications.show');
        Route::post('/applications/{application}/messages', [SeekerApplicationController::class, 'storeMessage'])->name('seeker.applications.messages.store');
        Route::get('/documents', [DocumentController::class, 'index'])->name('seeker.documents.index');
        Route::post('/documents', [DocumentController::class, 'store'])->name('seeker.documents.store');
        Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('seeker.documents.destroy');
        Route::post('/documents/{document}/default', [DocumentController::class, 'makeDefault'])->name('seeker.documents.make-default');
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
        Route::get('/profile/edit', [CompanyProfileController::class, 'edit'])->name('company.profile.edit');
        Route::post('/profile', [CompanyProfileController::class, 'update'])->name('company.profile.update');
        Route::get('/applications', [CompanyApplicationController::class, 'index'])->name('company.applications.index');
        Route::get('/applications/{application}', [CompanyApplicationController::class, 'show'])->name('company.applications.show');
        Route::post('/applications/{application}/messages', [CompanyApplicationController::class, 'storeMessage'])->name('company.applications.messages.store');
        Route::resource('jobs', CompanyJobController::class)->except(['show'])->names([
            'index' => 'company.jobs.index',
            'create' => 'company.jobs.create',
            'store' => 'company.jobs.store',
            'edit' => 'company.jobs.edit',
            'update' => 'company.jobs.update',
            'destroy' => 'company.jobs.destroy',
        ]);
        Route::post('/jobs/{job}/renew', [App\Http\Controllers\Company\JobController::class, 'renew'])->name('company.jobs.renew');
        Route::get('/packages', [App\Http\Controllers\Company\PackageController::class, 'index'])->name('company.packages.index');
        Route::post('/packages/request', [App\Http\Controllers\Company\PackageController::class, 'request'])->name('company.packages.request');
        
        // API route for cities (company)
        Route::get('/api/cities', function(\Illuminate\Http\Request $request) {
            $countryId = $request->get('country_id');
            if ($countryId) {
                $cities = \App\Models\City::where('country_id', $countryId)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get(['id', 'name']);
                return response()->json($cities);
            }
            return response()->json([]);
        })->name('company.api.cities');
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
        Route::get('/settings/countries', [App\Http\Controllers\Admin\CountryController::class, 'index'])->name('admin.settings.countries');
        Route::post('/settings/countries', [App\Http\Controllers\Admin\CountryController::class, 'store'])->name('admin.settings.countries.store');
        Route::put('/settings/countries/{country}', [App\Http\Controllers\Admin\CountryController::class, 'update'])->name('admin.settings.countries.update');
        Route::delete('/settings/countries/{country}', [App\Http\Controllers\Admin\CountryController::class, 'destroy'])->name('admin.settings.countries.destroy');
        Route::get('/settings/cities', [App\Http\Controllers\Admin\CityController::class, 'index'])->name('admin.settings.cities');
        Route::post('/settings/cities', [App\Http\Controllers\Admin\CityController::class, 'store'])->name('admin.settings.cities.store');
        Route::put('/settings/cities/{city}', [App\Http\Controllers\Admin\CityController::class, 'update'])->name('admin.settings.cities.update');
        Route::delete('/settings/cities/{city}', [App\Http\Controllers\Admin\CityController::class, 'destroy'])->name('admin.settings.cities.destroy');
        Route::get('/settings/job-types', [App\Http\Controllers\Admin\JobTypeController::class, 'index'])->name('admin.settings.job-types');
        Route::post('/settings/job-types', [App\Http\Controllers\Admin\JobTypeController::class, 'store'])->name('admin.settings.job-types.store');
        Route::put('/settings/job-types/{jobType}', [App\Http\Controllers\Admin\JobTypeController::class, 'update'])->name('admin.settings.job-types.update');
        Route::delete('/settings/job-types/{jobType}', [App\Http\Controllers\Admin\JobTypeController::class, 'destroy'])->name('admin.settings.job-types.destroy');
        Route::get('/settings/experience-levels', [App\Http\Controllers\Admin\ExperienceLevelController::class, 'index'])->name('admin.settings.experience-levels');
        Route::post('/settings/experience-levels', [App\Http\Controllers\Admin\ExperienceLevelController::class, 'store'])->name('admin.settings.experience-levels.store');
        Route::put('/settings/experience-levels/{experienceLevel}', [App\Http\Controllers\Admin\ExperienceLevelController::class, 'update'])->name('admin.settings.experience-levels.update');
        Route::delete('/settings/experience-levels/{experienceLevel}', [App\Http\Controllers\Admin\ExperienceLevelController::class, 'destroy'])->name('admin.settings.experience-levels.destroy');
        Route::get('/settings/smtp', [App\Http\Controllers\Admin\SmtpSettingsController::class, 'index'])->name('admin.settings.smtp');
        Route::post('/settings/smtp', [App\Http\Controllers\Admin\SmtpSettingsController::class, 'update'])->name('admin.settings.smtp.update');
        
        // API route for cities (admin)
        Route::get('/api/cities', function(\Illuminate\Http\Request $request) {
            $countryId = $request->get('country_id');
            if ($countryId) {
                $cities = \App\Models\City::where('country_id', $countryId)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get(['id', 'name']);
                return response()->json($cities);
            }
            return response()->json([]);
        })->name('api.cities');
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
        Route::get('/packages/requests', [App\Http\Controllers\Admin\PackageController::class, 'requests'])->name('admin.packages.requests');
        Route::post('/packages/requests/{packageRequest}/approve', [App\Http\Controllers\Admin\PackageController::class, 'approve'])->name('admin.packages.approve');
        Route::post('/packages/requests/{packageRequest}/reject', [App\Http\Controllers\Admin\PackageController::class, 'reject'])->name('admin.packages.reject');

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
