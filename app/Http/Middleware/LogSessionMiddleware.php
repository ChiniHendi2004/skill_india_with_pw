<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogSessionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Log session before saving
        Log::info('Session before saving:', session()->all());

        $response = $next($request);

        // Force save session and log after saving
        session()->save();
        Log::info('Session after saving:', session()->all());

        return $response;
    }
}
