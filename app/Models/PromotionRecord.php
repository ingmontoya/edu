<?php

namespace App\Models;

use App\Enums\PerformanceLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromotionRecord extends Model
{
    protected $fillable = [
        'student_id',
        'academic_year_id',
        'decision',
        'observations',
        'failed_subjects',
        'failed_count',
        'final_average',
        'performance_level',
        'decided_by',
        'decided_at',
    ];

    protected $casts = [
        'failed_subjects' => 'array',
        'final_average' => 'decimal:1',
        'decided_at' => 'datetime',
    ];

    // ============ Relationships ============

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function decidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    // ============ Scopes ============

    public function scopePromoted($query)
    {
        return $query->where('decision', 'promoted');
    }

    public function scopeRetained($query)
    {
        return $query->where('decision', 'retained');
    }

    // ============ Helpers ============

    public function getDecisionLabel(): string
    {
        return match($this->decision) {
            'promoted' => 'Promovido',
            'retained' => 'No Promovido',
            'early_promoted' => 'Promoción Anticipada',
            'conditional' => 'Promoción Condicional',
            default => $this->decision,
        };
    }

    public function getDecisionColor(): string
    {
        return match($this->decision) {
            'promoted', 'early_promoted' => 'green',
            'conditional' => 'yellow',
            'retained' => 'red',
            default => 'gray',
        };
    }

    public function getPerformanceLevelEnum(): ?PerformanceLevel
    {
        if (!$this->performance_level) {
            return null;
        }
        return PerformanceLevel::tryFrom($this->performance_level);
    }

    public function isPromoted(): bool
    {
        return in_array($this->decision, ['promoted', 'early_promoted', 'conditional']);
    }
}
