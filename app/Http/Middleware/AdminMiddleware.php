<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth::check()){
            if(Auth::user()->role == 1 && Auth::user()->status == 1){         
                return $next($request);
            }
            else {
                Auth::logout();
                if($request->ajax()) // This is what i am needing.
                {
                    return response()->json(['error' => 'error', 'message' => 'Session timeout'], 302);
                }
                return redirect()->route('home');
            }
        }
        else {
             Auth::logout();
            if($request->ajax()) // This is what i am needing.
            {
                return response()->json(['error' => 'error', 'message' => 'Session timeout'], 302);
            }
            return redirect()->route('home');
         }
    }
}
