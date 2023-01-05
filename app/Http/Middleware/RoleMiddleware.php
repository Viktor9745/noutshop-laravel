<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$rolenames)
    {
        if(Auth::check()){
            foreach($rolenames as $rname){
                if(Auth::user()->role->name==$rname){
                    return $next($request);
                }
            }
        }else{
            return redirect()->route('login.form');
        }
        return response()->view('errors.noperm');

    }
}
