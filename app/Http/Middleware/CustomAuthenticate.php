<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Auth\AuthenticationException;

class CustomAuthenticate extends Middleware
{
    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException(
            'Unauthenticated.',
            $guards,
            $this->redirectTo($request, $guards)
        );
    }

    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            if (in_array('admin', $request->route()?->middleware() ?? [])) {
                return route('admin.login');
            }
            return route('login');
        }
    }
}
