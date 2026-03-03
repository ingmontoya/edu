<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Achievement extends Model
{
    protected $fillable = [
        'subject_id',
        'period_id',
        'code',
        'description',
        'type',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ============ Relationships ============

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    public function indicators(): HasMany
    {
        return $this->hasMany(AchievementIndicator::class)->orderBy('order');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_achievements')
            ->withPivot(['status', 'observations', 'evaluated_by', 'evaluated_at'])
            ->withTimestamps();
    }

    public function studentAchievements(): HasMany
    {
        return $this->hasMany(StudentAchievement::class);
    }

    // ============ Scopes ============

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForSubjectPeriod($query, int $subjectId, int $periodId)
    {
        return $query->where('subject_id', $subjectId)
            ->where('period_id', $periodId);
    }

    // ============ Helpers ============

    public function getTypeLabel(): string
    {
        return match($this->type) {
            'cognitive' => 'Cognitivo',
            'procedural' => 'Procedimental',
            'attitudinal' => 'Actitudinal',
            default => $this->type,
        };
    }
}
