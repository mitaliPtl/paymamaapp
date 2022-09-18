<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Auth;
use Config;
use DB;
use App\UserLoginSessionDetail;
use Closure;
use Illuminate\Http\Request;
use Session;


class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
      
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
    public function handle($request, Closure $next,...$guards)
    {
        
        if($request->session() != null)
        {
         
        if ( Auth::user()['activated_status'] == 'YES') {
            return $next($request);
        }
        else {
            return redirect('/logout');
        }
        }
        else
        {
            return redirect('/logout');
        }
        
       // if (! $request->expectsJson()) {
         //   return route('login');
        //}
    }
}
