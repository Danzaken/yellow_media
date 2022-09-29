<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\InputBag;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['auth']->viaRequest('api', function ($request) {
            /** @var Request $request*/
            if ($request->header('Authorization')) {
                $user = User::query()->where(['api_token' => $request->header('Authorization')])->first();
                if(!empty($user)){
                    $request->request->add(['userId' => $user->id]);
                }
                return $user;
            }
        });
    }
}
