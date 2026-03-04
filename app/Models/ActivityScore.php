<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityScore extends Model
{
    protected $fillable = [
        'activity_id', 'student_id', 'score',
    ];

    protected $casts = [
        'score' => 'decimal:1',
    ];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(GradeActivity::class, 'activity_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
