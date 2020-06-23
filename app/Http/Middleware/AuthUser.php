<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Session;
use Closure;

class AuthUser
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
        if (session()->get('user') === null) {
            return redirect()->route('Login');
        }

        return $next($request);
    }
}
