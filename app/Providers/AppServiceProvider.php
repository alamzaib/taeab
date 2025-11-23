<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share notification count with header component
        View::composer('components.header', function ($view) {
            $notificationCount = 0;
            
            if (Auth::guard('seeker')->check()) {
                $notificationCount = Auth::guard('seeker')->user()->unreadNotifications()->count();
            } elseif (Auth::guard('company')->check()) {
                $notificationCount = Auth::guard('company')->user()->unreadNotifications()->count();
            }
            
            $view->with('headerNotificationCount', $notificationCount);
        });
    }
}
