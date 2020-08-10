<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Http\Request;

class HasRole
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        getUserInfo();
        $sessionRole = session()->get('role');
        if ( $sessionRole == $role) {
            return $next($request);
        }
        session()->forget('role');
        return redirect('/');
    }
}
