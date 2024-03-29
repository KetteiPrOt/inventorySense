<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        // Implicitly grant Role::$superAdmin role all permissions
        Gate::before(function (User $user, string $ability) {
            return $user->hasRole(Role::$superAdmin) ? true : null;
        });
    }
}
