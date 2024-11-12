<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class CheckRedirection
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
        $slug = $request->route('slug');
        $redirection = DB::table('redirections')->where('slug', $slug)->first();

        if ($redirection) {
            // Perform a redirect to the target URL
            return redirect($redirection->page, 301);
        }

        return $next($request);
    }
}
