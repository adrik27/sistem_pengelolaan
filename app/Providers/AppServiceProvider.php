<?php

namespace App\Providers;

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
        Gate::define('admin', function ($user) {
            return ($user->department_id == 1 && $user->jabatan_id == 1) || ($user->jabatan_id == 3);
        });
        Gate::define('user', function ($user) {
            return ($user->jabatan_id == 2);
        });
    }
}
