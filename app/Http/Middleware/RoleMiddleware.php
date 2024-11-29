<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role_id): Response
    {
        $roles = is_array($role_id) ? $role_id : explode(',', $role_id);

        if (in_array($request->user()->role_id, $roles)) {
            return $next($request);
        }


        return redirect()->back();
    }
}
