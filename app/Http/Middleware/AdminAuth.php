<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
     public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('admin_id')) {
            // If not logged in, redirect to student login page
            return redirect('/Admin');
        }

        return $next($request);
    }
}
