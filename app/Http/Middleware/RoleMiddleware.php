<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $role)
{
    $userRole = session('user_data.role');

    if ($userRole !== $role) {
        return redirect()->route('home')->with('error', 'Unauthorized access!');
    }

    return $next($request);
}

}
