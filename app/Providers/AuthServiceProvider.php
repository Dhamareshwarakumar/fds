<?php

namespace App\Providers;

use Error;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\GenericUser;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class AuthServiceProvider extends ServiceProvider
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
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->header('Authorization')) {
                try {
                    $api_token = explode(' ', $request->header('Authorization'))[1];
                    $decoded = JWT::decode($api_token, new Key(env('JWT_SECRET'), 'HS256'));
                } catch (Exception $e) {
                    return null;
                }

                $user = DB::table('users')->where('username', $decoded->email)->first();

                if ($user) {
                    return new GenericUser([
                        'user_id' => $user->user_id,
                        'email' => $user->username,
                        'role' => $user->role,
                    ]);
                } else {
                    return null;
                }
            } else {
                return null;
            }
        });
    }
}
