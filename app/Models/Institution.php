<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends Model
{
    protected $fillable = [
        'name', 'nit', 'dane_code', 'logo', 'address',
        'phone', 'email', 'city', 'department', 'rector_name', 'grading_scale',
        'education_level',
        'ai_analyses_limit', 'ai_analyses_used', 'ai_quota_resets_at',
    ];

    protected $casts = [
        'grading_scale' => 'array',
        'ai_quota_resets_at' => 'date',
    ];

    /**
     * Reset monthly quota if the reset date has passed, then check availability.
     */
    public function hasAiQuota(): bool
    {
        $this->refreshQuotaIfNeeded();

        return $this->ai_analyses_used < $this->ai_analyses_limit;
    }

    public function incrementAiUsage(): void
    {
        $this->increment('ai_analyses_used');
    }

    public function aiQuotaInfo(): array
    {
        $this->refreshQuotaIfNeeded();

        return [
            'used' => $this->ai_analyses_used,
            'limit' => $this->ai_analyses_limit,
            'remaining' => max(0, $this->ai_analyses_limit - $this->ai_analyses_used),
            'resets_at' => $this->ai_quota_resets_at?->toDateString(),
        ];
    }

    private function refreshQuotaIfNeeded(): void
    {
        if ($this->ai_quota_resets_at === null || $this->ai_quota_resets_at->isPast()) {
            $this->update([
                'ai_analyses_used' => 0,
                'ai_quota_resets_at' => now()->addMonthNoOverflow()->startOfMonth(),
            ]);
            $this->refresh();
        }
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function academicYears(): HasMany
    {
        return $this->hasMany(AcademicYear::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function areas(): HasMany
    {
        return $this->hasMany(Area::class);
    }

    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function activeYear()
    {
        return $this->academicYears()->where('is_active', true)->first();
    }
}
