<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // ponytail: defaults to penjaga for safety, aborts 403 on role mismatch
        if (! $request->user()?->isRole(...array_map(Role::from(...), $roles))) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
