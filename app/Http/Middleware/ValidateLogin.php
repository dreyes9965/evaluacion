<?php

namespace App\Http\Middleware;

use Closure;

class ValidateLogin
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
        if(auth()->user()->is_active == '1'){
            return $next($request);
        }else{
            auth()->logout();
            // redirect to login
            return redirect('/login')->with('message', 'No esta autorizado para acceder');
        }
    }
}
