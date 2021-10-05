<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->registerPolicies();

        Gate::define('access-users', function (User $user) {
            return $user->role === 'super_admin' || $user->role === 'admin' ? true : false;
        });

        Gate::define('access-user', function (User $user) {
            return $user->role === 'super_admin' ? true : false;
        });
    }
}
