<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Company
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

            if (Auth::guard('company')->user()->id) {
               // dd("ooo");
                return $next($request);

            } else {
               // dd("yy");
                return redirect('/login');
            }

    }
}
