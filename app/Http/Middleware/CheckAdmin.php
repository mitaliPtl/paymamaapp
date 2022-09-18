<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Config;

class CheckAdmin
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
        if (Auth::check() && Auth::user()->roleId != Config::get('constants.ADMIN')) {
            return redirect('/permission-denied');
        }
        return $next($request);
    }
}
