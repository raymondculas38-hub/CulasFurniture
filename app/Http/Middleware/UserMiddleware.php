<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Enforce customer-role access control for customer specific operations.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login')->with('warning', 'Please sign in to proceed.');
        }

        if (!auth()->user()->isUser()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden. Customer account required.'], 403);
            }
            return redirect()->route('home')->with('error', 'Access restricted to customer accounts.');
        }

        return $next($request);
    }
}
