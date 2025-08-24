<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
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
        Paginator::useBootstrapFive();

        Gate::define('admin', function ($user) {
            return $user->role->name === 'Admin';
        });

        Gate::define('surveyor', function ($user) {
            return $user->role->name === 'Surveyor';
        });

        Gate::define('distributor', function ($user) {
            return $user->role->name === 'Distributor';
        });

        Gate::define('kepala', function ($user) {
            return $user->role->name === 'Kepala';
        });
    }
}
