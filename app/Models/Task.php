<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'institution_id', 'teacher_id', 'group_id', 'subject_id',
        'title', 'instructions', 'attachment_path', 'attachment_name',
        'due_date', 'is_published',
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_published' => 'boolean',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function studentTasks(): HasMany
    {
        return $this->hasMany(StudentTask::class);
    }
}
