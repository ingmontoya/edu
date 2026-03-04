<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GradeActivity extends Model
{
    protected $fillable = [
        'subject_id', 'period_id', 'teacher_id',
        'name', 'type', 'weight', 'date', 'order',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'date' => 'date',
    ];

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

    public function scores(): HasMany
    {
        return $this->hasMany(ActivityScore::class, 'activity_id');
    }
}
