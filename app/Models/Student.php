<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'user_id', 'group_id', 'enrollment_code', 'enrollment_date', 'status',
        'simat_code', 'stratum', 'health_insurer', 'ethnicity',
        'disability_type', 'municipality', 'birth_municipality',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function guardians(): BelongsToMany
    {
        return $this->belongsToMany(Guardian::class, 'student_guardian')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function gradeRecords(): HasMany
    {
        return $this->hasMany(GradeRecord::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    // SIEE relationships
    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'student_achievements')
            ->withPivot(['status', 'observations', 'evaluated_by', 'evaluated_at'])
            ->withTimestamps();
    }

    public function studentAchievements(): HasMany
    {
        return $this->hasMany(StudentAchievement::class);
    }

    public function remedialActivities(): BelongsToMany
    {
        return $this->belongsToMany(RemedialActivity::class, 'student_remedials')
            ->withPivot(['status', 'grade', 'submission_notes', 'teacher_feedback', 'submitted_at', 'graded_at'])
            ->withTimestamps();
    }

    public function studentRemedials(): HasMany
    {
        return $this->hasMany(StudentRemedial::class);
    }

    public function promotionRecords(): HasMany
    {
        return $this->hasMany(PromotionRecord::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // SIEE helpers
    public function hasFailingGrades(int $periodId): bool
    {
        return $this->gradeRecords()
            ->where('period_id', $periodId)
            ->where('grade', '<', 3.0)
            ->exists();
    }

    public function getFailingSubjects(int $periodId)
    {
        return $this->gradeRecords()
            ->where('period_id', $periodId)
            ->where('grade', '<', 3.0)
            ->with('subject')
            ->get();
    }

    public function getPendingRemedials()
    {
        return $this->studentRemedials()
            ->where('status', 'pending')
            ->with('remedialActivity.subject')
            ->get();
    }
}
