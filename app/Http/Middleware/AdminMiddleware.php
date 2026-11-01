<?php

namespace App\Http\Middleware;
use Auth;


use Closure;

class AdminMiddleware
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
        if(Auth::check()){
            // check auth user role (I don't know how you can implement this for yourself, this is just for me)
            if(Auth::user()->userRole->role->name == "ADMIN" || Auth::user()->userRole->role->name == "COORDINATOR" || Auth::user()->userRole->role->name == "MASTER") {
                return $next($request);
            } else {
                return redirect()->route('home'); // for admins
            }
        }
    }
}
