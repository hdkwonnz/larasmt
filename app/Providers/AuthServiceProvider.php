<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isAdmin', function ($user) { ////
            return $user->role === 'admin';
        });
        Gate::define('isEditor', function ($user) { ////
            return $user->role === 'editor';
        });
        Gate::define('isUser', function ($user) { ////
            return $user->role === 'user';
        });

        Gate::define('isAdminOrEditor', function($user){
            if ($user->role === 'admin' || $user->role === 'editor'){
                return true;
            }
        });
    }
}