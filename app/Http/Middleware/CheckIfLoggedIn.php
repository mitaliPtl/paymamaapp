<?php

namespace App\Http\Middleware;

use App\UserLoginSessionDetail;
use Closure;
use Illuminate\Http\Request;

class CheckIfLoggedIn
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
        $userLgInSession = UserLoginSessionDetail::where('user_id', $request->user_id)
            ->where('role_id', $request->role_id)->first();
        if (isset($userLgInSession) && $userLgInSession->apiKey == $request->token) {
            return $next($request);
        } else {
            return redirect('/authentication-failure');
        }
    }
}
