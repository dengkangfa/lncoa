<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class CheckMenu
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
        if(Auth::user()->hasMenu($menu)) {
             return redirect()->route('/');
        }
        return $next($request);
    }
}
