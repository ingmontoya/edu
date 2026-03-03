<?php

namespace App\Http\Middleware;

use App\Services\TenantService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Resolves the tenant (institution) from the authenticated user
     * and sets it in the TenantService for global access.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $institution = TenantService::resolveFromUser($user);

            if ($institution) {
                TenantService::setInstitution($institution);
            } elseif (!TenantService::isSuperAdmin()) {
                // User is not a super admin and has no institution
                // This could be a misconfigured user
                return response()->json([
                    'message' => 'No institution associated with this user.',
                ], 403);
            }
        }

        $response = $next($request);

        // Clear tenant context after request
        TenantService::clear();

        return $response;
    }
}
