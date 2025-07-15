<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthMidleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return response()->json(['Message' => 'Zəhmət olmasa giriş  edin.!'], 401);
        }
        if (in_array(Auth::user()->role, $roles)) {
            return $next($request);
        }
        return response()->json(['Message' => 'Bura daxil olmağa icazə verilmir.!'], 403);
    }
}
