<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Staff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $user = app('Dingo\Api\Auth\Auth')->user();
        
        if ($user) {
            if ($user->is_super) {
                return $next($request);
            }
        }

        return response('Unauthorized.', 401);
    }
}
