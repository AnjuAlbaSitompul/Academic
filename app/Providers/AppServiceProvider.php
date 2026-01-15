<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('*', function ($view) {
            $view->with('authUser', Auth::user());
        });

        View::composer('*', function ($view) {

            $role = Auth()->check() ? auth()->user()->role : null;

            $sidebar = match ($role) {
                'admin' => config('sidebar_admin'),
                'guru'  => config('sidebar_guru'),
                default => [],
            };

            $view->with('sidebar', $sidebar);
        });
    }
}
