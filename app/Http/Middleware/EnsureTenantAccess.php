<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Add this line

class EnsureTenantAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->tenant_id != $request->tenant_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}