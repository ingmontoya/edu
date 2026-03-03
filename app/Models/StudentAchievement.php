<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAchievement extends Model
{
    protected $fillable = [
        'student_id',
        'achievement_id',
        'status',
        'observations',
        'evaluated_by',
        'evaluated_at',
    ];

    protected $casts = [
        'evaluated_at' => 'datetime',
    ];

    // ============ Relationships ============

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function achievement(): BelongsTo
    {
        return $this->belongsTo(Achievement::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }

    // ============ Scopes ============

    public function scopeAchieved($query)
    {
        return $query->where('status', 'achieved');
    }

    public function scopeNotAchieved($query)
    {
        return $query->where('status', 'not_achieved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // ============ Helpers ============

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'achieved' => 'Alcanzado',
            'in_progress' => 'En Progreso',
            'not_achieved' => 'No Alcanzado',
            'pending' => 'Pendiente',
            default => $this->status,
        };
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'achieved' => 'green',
            'in_progress' => 'yellow',
            'not_achieved' => 'red',
            'pending' => 'gray',
            default => 'gray',
        };
    }

    public function isAchieved(): bool
    {
        return $this->status === 'achieved';
    }
}
