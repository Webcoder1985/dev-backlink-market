<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) // I included this check because you have it, but it really should be part of your 'auth' middleware, most likely added as part of a route group.
            return redirect('login');

        $user = Auth::user();
        if(in_array('admin', $roles) && $user->user_status == 3)
        {
            return $next($request);
        }
        elseif(in_array('member', $roles) && $user->user_status != 3)
        {
            return $next($request);
        }
        else
        {
            abort(403);
        }
        
        return redirect('login');
    }
}
