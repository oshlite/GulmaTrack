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
        // Cek apakah user sudah login
        if (!auth()->check()) {
            // PENTING: Handle AJAX requests dengan JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: Please login first'
                ], 401);
            }
            return redirect()->route('login');
        }

        // Cek apakah user adalah admin
        if (!auth()->user()->isAdmin()) {
            // PENTING: Handle AJAX requests dengan JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Forbidden: Admin access required'
                ], 403);
            }
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Cek apakah user aktif
        if (!auth()->user()->is_active) {
            auth()->logout();
            // PENTING: Handle AJAX requests dengan JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized: Account is inactive'
                ], 401);
            }
            return redirect()->route('login')->with('error', 'Akun Anda tidak aktif.');
        }

        return $next($request);
    }
}