<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Admin
{
    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->IsAdmin()) {
            var_dump(Auth::check() && Auth::user()->IsAdmin());
            return $next($request);
        }
        return redirect('/login');
    }
}