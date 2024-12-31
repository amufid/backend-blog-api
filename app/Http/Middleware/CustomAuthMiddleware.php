<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // $log1 = $request->bearerToken();
        // $log2 = $request->cookie('laravel_session');

        // Periksa apakah token ada di header Authorization
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized, token not found.'
            ], 401);
        }

        // Periksa apakah token valid dengan Auth::guard
        if (!Auth::guard('sanctum')->check() || !Auth::check()) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 401);
        }

        return $next($request);
    }
}
