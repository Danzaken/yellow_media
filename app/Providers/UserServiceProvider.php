<?php

namespace App\Providers;

use App\Services\CompanyService;
use App\Services\UserService;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(UserService::class, function ($app) {
            return new UserService();
        });
        $this->app->singleton(CompanyService::class, function ($app) {
            return new CompanyService();
        });
    }

}
