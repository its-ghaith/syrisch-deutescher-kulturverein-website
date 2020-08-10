<?php

namespace App\Http\Middleware\Auth;

use Closure;

class IsNotLogin
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
        if (! getUserInfo()['isLogin']){
            return $next($request);
        }
        return back();
    }
}
