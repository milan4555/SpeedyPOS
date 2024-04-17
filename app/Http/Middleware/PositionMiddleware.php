<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PositionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $url = $request->url();
        if ($user->position == 'admin' or $user->position == 'both') {
            return $next($request);
        }
        if ($user->position == 'cashier' and str_contains($url, 'cashRegister')) {
            return $next($request);
        }
        if ($user->position == 'storage' and str_contains($url, 'storage')) {
            return $next($request);
        }

        return redirect()->back()->with('error', 'Ehhez a folyamathoz nincsen jogosultságod!');
    }
}
