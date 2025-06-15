<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('student_id')) {
            // If not logged in, redirect to student login page
            return redirect('/');
        }

        return $next($request);
    } 
  
}
