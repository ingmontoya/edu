<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentRemedial extends Model
{
    protected $fillable = [
        'student_id',
        'remedial_activity_id',
        'status',
        'grade',
        'submission_notes',
        'teacher_feedback',
        'submitted_at',
        'graded_at',
        'graded_by',
    ];

    protected $casts = [
        'grade' => 'decimal:1',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
    ];

    // ============ Relationships ============

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function remedialActivity(): BelongsTo
    {
        return $this->belongsTo(RemedialActivity::class);
    }

    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    // ============ Scopes ============

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeGraded($query)
    {
        return $query->where('status', 'graded');
    }

    // ============ Helpers ============

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Pendiente',
            'submitted' => 'Entregado',
            'graded' => 'Calificado',
            'excused' => 'Excusado',
            default => $this->status,
        };
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'submitted' => 'blue',
            'graded' => 'green',
            'excused' => 'gray',
            default => 'gray',
        };
    }

    public function isPassed(): bool
    {
        return $this->grade !== null && $this->grade >= 3.0;
    }
}
