<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth; 
use Closure;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'admins')
    {
        if (Auth::guard($guard)->guest()){
            if($request->ajax() || $request->wantsJson()){
                return response('Unauthorized.',401);
            }else {
                return redirect()->guest('admin');
            }
        }


        return $next($request);
    }
}
