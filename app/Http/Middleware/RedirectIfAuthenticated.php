<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Jika user sudah login DAN tidak sedang akses halaman login, redirect
                if (!$request->is('login')) {
                    // Jika user adalah admin, redirect ke admin dashboard
                    if (Auth::user()->isAdmin()) {
                        return redirect()->route('admin.dashboard');
                    }
                    
                    // Jika user adalah guest, redirect ke home
                    return redirect(RouteServiceProvider::HOME);
                }
            }
        }

        return $next($request);
    }
}