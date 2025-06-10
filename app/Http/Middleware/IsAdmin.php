<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && strtolower(Auth::user()->role) === 'admin') {
            return $next($request);
        }
        return redirect()->route('dashboard.index')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');
    }
}