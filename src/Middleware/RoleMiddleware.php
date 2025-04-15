<?php

namespace Techaxion\UserAccess\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check() || !Auth::user()->hasRole($role)) {
            abort(403);
        }
        return $next($request);
    }
}
