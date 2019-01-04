<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class BillPaid
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

            if (Auth::user()->bill_unpaid && Auth::user()->subkey != 'free') {
                return redirect('/user/billing/update');
            }

        }

        return $next($request);
    }
}
