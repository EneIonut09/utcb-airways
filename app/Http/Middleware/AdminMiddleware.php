<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Trebuie să vă autentificați.');
        }

        if (Auth::user()->role !== 'admin') {
            return redirect('/home')->with('error', 'Nu aveți permisiuni de administrator.');
        }

        return $next($request);
    }
}