<?php

namespace App\Models;

use App\Enums\PerformanceLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class GradeRecord extends Model
{
    protected $fillable = [
        'student_id', 'subject_id', 'period_id', 'teacher_id',
        'grade', 'observations', 'recommendations',
    ];

    protected $casts = [
        'grade' => 'decimal:1',
    ];

    protected $appends = ['performance_level', 'performance_label'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

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

    protected function performanceLevel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->grade !== null
                ? PerformanceLevel::fromGrade((float) $this->grade)->value
                : null,
        );
    }

    protected function performanceLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->grade !== null
                ? PerformanceLevel::fromGrade((float) $this->grade)->label()
                : null,
        );
    }
}
