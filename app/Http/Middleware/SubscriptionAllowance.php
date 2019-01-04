<?php

/*  Check which subscription the user has and then request the amount of live tours they have remaining */

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Plan as Plan;
use App\Tours as Tours;

class SubscriptionAllowance
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

        $userSubKey = Auth::user()->subkey;
        $subscription = Plan::where('subkey', $userSubKey)->first();
        $allowance = $subscription->max_tours;

        $publicToursCount = Auth::user()->public_tours;
        $toursLeft = $allowance - $publicToursCount;

        if ($toursLeft === 0 ) {
            return redirect('upgrade-subscription');
        }

        return $next($request);
    }
}
