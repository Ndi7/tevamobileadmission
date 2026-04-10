<?php

namespace App\Http\Middleware;

use Closure;

class AdminAuth
{
    public function handle($request, Closure $next)
    {
        if (!session()->has('adminName')) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
