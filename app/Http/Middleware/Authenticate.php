<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Symfony\Component\Routing\Route;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            if ($request->is('panel') || $request->is('panel/*')) {
                return route('loginadmin');
            } else {
                return route('login');
            }
        }
    }
}
