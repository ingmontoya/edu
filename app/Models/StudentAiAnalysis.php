<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAiAnalysis extends Model
{
    protected $fillable = [
        'student_id',
        'period_id',
        'risk_level',
        'risk_score',
        'narrative',
        'recommendations',
        'generated_by',
    ];

    protected $casts = [
        'recommendations' => 'array',
        'risk_score' => 'decimal:1',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
