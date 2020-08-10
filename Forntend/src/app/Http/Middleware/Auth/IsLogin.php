<?php

namespace App\Http\Middleware\Auth;

use Closure;
include_once(app_path() . '/Helper/restClient.php');

class IsLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (getUserInfo()['isLogin']){
            return $next($request);
        }
        return redirect('login');
    }
}
