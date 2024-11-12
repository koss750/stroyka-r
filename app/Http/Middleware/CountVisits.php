<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis as Redis;

class CountVisits
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
        $fullUrl = $request->fullUrl();
        //Redis::incr("visits:$fullUrl");

        return $next($request);
    }
}
