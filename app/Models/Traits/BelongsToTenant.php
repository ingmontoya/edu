<?php

namespace App\Models\Traits;

use App\Models\Institution;
use App\Services\TenantService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    /**
     * Boot the trait.
     */
    public static function bootBelongsToTenant(): void
    {
        // Automatically scope queries to current tenant
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (TenantService::hasTenant()) {
                $builder->where(
                    $builder->getModel()->getTable() . '.institution_id',
                    TenantService::getInstitutionId()
                );
            }
        });

        // Automatically set institution_id when creating
        static::creating(function ($model) {
            if (TenantService::hasTenant() && empty($model->institution_id)) {
                $model->institution_id = TenantService::getInstitutionId();
            }
        });
    }

    /**
     * Get the institution that owns this model.
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Scope query to a specific institution.
     */
    public function scopeForInstitution(Builder $query, int $institutionId): Builder
    {
        return $query->withoutGlobalScope('tenant')
            ->where('institution_id', $institutionId);
    }

    /**
     * Scope query without tenant filtering.
     */
    public function scopeWithoutTenant(Builder $query): Builder
    {
        return $query->withoutGlobalScope('tenant');
    }
}
