<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\User;

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

        //hard coded my admin user check to my email id???
        //runs before any auth
        Gate::before( function($user, $ability){
            //user is authenticate at this stage
            //check the user ability and return result of true or false
            if ($user->abilities()->contains($ability)) {
                return true;
            }
        });
    }
}
