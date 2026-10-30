<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Valida que el usuario tenga uno de los roles permitidos.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // Permite múltiples roles separados por coma (ej: role:developer,admin)
        $allowedRoles = [];
        foreach ($roles as $role) {
            foreach (explode(',', $role) as $subRole) {
                $allowedRoles[] = trim($subRole);
            }
        }

        $userRoleSlug = $request->user()->role->slug ?? null;

        if (! in_array($userRoleSlug, $allowedRoles)) {
            abort(403, 'Acceso denegado: tu cuenta no posee los privilegios requeridos.');
        }

        return $next($request);
    }
}