<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'institution_id',
        'student_id',
        'subject_id',
        'academic_year_id',
        'semester_number',
        'status',
        'final_grade',
    ];

    protected $casts = [
        'final_grade' => 'decimal:2',
        'semester_number' => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function scopeEnrolled(Builder $query): Builder
    {
        return $query->where('status', 'enrolled');
    }
}
