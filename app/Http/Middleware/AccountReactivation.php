<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AccountReactivation
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


        if (!Auth::guest()) {
            if (Auth::user()->account_deleted) {
                return redirect('/user/reactivation');
            }
        }

        return $next($request);
    }
}
