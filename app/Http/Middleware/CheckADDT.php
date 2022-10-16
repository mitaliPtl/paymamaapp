<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Config;

class CheckADDT
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
        if (Auth::check() && (Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.DISTRIBUTOR')) || (Auth::check() && Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.SYSTEM_ADMIN')) || (Auth::userRoleAlias() == Config::get('constants.ROLE_ALIAS.MASTER_DISTRIBUTOR'))) {
            return $next($request);
        }
        return redirect('/permission-denied');
    }
}
