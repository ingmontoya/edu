<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RemedialActivity extends Model
{
    protected $fillable = [
        'subject_id',
        'period_id',
        'teacher_id',
        'title',
        'description',
        'instructions',
        'type',
        'assigned_date',
        'due_date',
        'max_grade',
        'is_active',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'due_date' => 'date',
        'max_grade' => 'decimal:1',
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

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_remedials')
            ->withPivot(['status', 'grade', 'submission_notes', 'teacher_feedback', 'submitted_at', 'graded_at', 'graded_by'])
            ->withTimestamps();
    }

    public function studentRemedials(): HasMany
    {
        return $this->hasMany(StudentRemedial::class);
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

    public function scopePending($query)
    {
        return $query->where('due_date', '>=', now()->toDateString());
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now()->toDateString());
    }

    // ============ Helpers ============

    public function getTypeLabel(): string
    {
        return match($this->type) {
            'recovery' => 'Recuperación',
            'reinforcement' => 'Refuerzo',
            'leveling' => 'Nivelación',
            default => $this->type,
        };
    }

    public function isOverdue(): bool
    {
        return $this->due_date->isPast();
    }

    public function daysRemaining(): int
    {
        return max(0, now()->diffInDays($this->due_date, false));
    }
}
