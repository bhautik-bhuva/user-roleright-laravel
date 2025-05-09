<?php

namespace Techaxion\UserAccess\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminGuardMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
