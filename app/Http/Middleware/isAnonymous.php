<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class isAnonymous
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() == FALSE) {
            return $next($request);
        } else {
            if (Auth::user()->role == 'HEAD_STAFF') {
                return redirect()->route('staff.index')->with('failed', 'Anda Sudah Login!');
            } else if (Auth::user()->role == 'STAFF') {
                return redirect()->route('responses.index')->with('failed', 'Anda Sudah Login!');
            } else {
                return redirect()->route('report.data-report')->with('failed', 'Anda Sudah Login!');
            }
        }
    }
}
