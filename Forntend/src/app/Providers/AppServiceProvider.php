<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
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
        Blade::if('not_login', function () {
            $res = getUserInfo();
            if (!isset($res['isLogin']) || !$res['isLogin']) {
                return true;
            } else {
                return false;
            }
        });

        Blade::if('hasRole', function ($roles) {
            $sessionRole = session()->get('role');
            foreach ($roles as $role) {
                if ($role == 'guest') {
                    if (!isset($sessionRole)) {
                        return true;
                    }
                }
                elseif ($sessionRole == $role) {
                    return true;
                }
            }
            return false;
        });
    }
}
