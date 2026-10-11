<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user() && auth()->user()->hasRole('admin')){

            return $next($request);
        }
        elseif(auth()->user() && auth()->user()->hasRole('user')){

            return $next($request);
        }
        else{

            toastr()->warning('You are not allow to access this route !');
            return redirect()->back();
        }

    }

}
