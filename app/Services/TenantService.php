<?php

namespace App\Services;

use App\Models\Institution;
use App\Models\User;

class TenantService
{
    private static ?Institution $currentInstitution = null;

    /**
     * Set the current tenant institution.
     */
    public static function setInstitution(?Institution $institution): void
    {
        self::$currentInstitution = $institution;
    }

    /**
     * Get the current tenant institution.
     */
    public static function getInstitution(): ?Institution
    {
        return self::$currentInstitution;
    }

    /**
     * Get the current institution ID.
     */
    public static function getInstitutionId(): ?int
    {
        return self::$currentInstitution?->id;
    }

    /**
     * Check if a tenant is currently set.
     */
    public static function hasTenant(): bool
    {
        return self::$currentInstitution !== null;
    }

    /**
     * Clear the current tenant.
     */
    public static function clear(): void
    {
        self::$currentInstitution = null;
    }

    /**
     * Resolve tenant from an authenticated user.
     */
    public static function resolveFromUser(User $user): ?Institution
    {
        if ($user->institution_id) {
            return Institution::find($user->institution_id);
        }

        // For super admins (no institution_id), try to get from teacher/student profile
        if ($user->teacher?->institution_id) {
            return Institution::find($user->teacher->institution_id);
        }

        return null;
    }

    /**
     * Check if the current user is a super admin (no institution bound).
     */
    public static function isSuperAdmin(): bool
    {
        $user = auth()->user();
        return $user && $user->institution_id === null && $user->role === 'admin';
    }

    /**
     * Execute a callback within a specific tenant context.
     */
    public static function run(Institution $institution, callable $callback): mixed
    {
        $previous = self::$currentInstitution;
        self::setInstitution($institution);

        try {
            return $callback();
        } finally {
            self::setInstitution($previous);
        }
    }
}
