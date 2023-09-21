<?php

namespace App\Providers;

use App\Models\User;
use Blade;
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

        // Gate Facade
        Gate::define('admin', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('rootadmin', function (User $user) {
            return $user->isRootAdmin();
        });

        // Custom Blade Directive
        Blade::if('admin', function () {
            return request()->user()?->isAdmin();
        });

        Blade::if('rootadmin', function () {
            return request()->user()?->isRootAdmin();
        });

        // Activate tailwind pagination
        Paginator::useTailwind();
    }
}
