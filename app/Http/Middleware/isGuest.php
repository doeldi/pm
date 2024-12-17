<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class isGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->role == 'GUEST') {
            return $next($request);
        } else {
            if (Auth::user()->role == 'HEAD_STAFF') {
                return redirect()->route('staff.index')->with('failed', 'Anda bukan guest');
            } else {
                return redirect()->route('responses.index')->with('failed', 'Anda bukan guest');
            } 
        }
    }
}
