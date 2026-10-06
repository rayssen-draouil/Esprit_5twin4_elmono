<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        $role = strtolower((string) ($user?->role ?? ''));
        $aliases = ['gestionnaire' => 'manager', 'citoyen' => 'citizen'];

        if (! $user || ! in_array($aliases[$role] ?? $role, array_map(
            fn (string $value) => $aliases[strtolower($value)] ?? strtolower($value),
            $roles
        ), true)) {
            abort(403);
        }

        return $next($request);
    }
}
