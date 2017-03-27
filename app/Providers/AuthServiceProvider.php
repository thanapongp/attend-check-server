<?php

namespace AttendCheck\Providers;

use AttendCheck\Auth\MobileAppGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use AttendCheck\Auth\MobileUserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'AttendCheck\Model' => 'AttendCheck\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::extend('apptoken', function ($app, $name, array $config) {
            return new MobileAppGuard(
                Auth::createUserProvider($config['provider']), $app->make('request')
            );
        });

        Auth::provider('devices', function ($app, array $config) {
            return new MobileUserProvider($config['model']);
        });
    }
}
