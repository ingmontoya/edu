<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisciplinaryRecord extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'institution_id', 'student_id', 'reporter_id', 'period_id',
        'type', 'category', 'description', 'date', 'location',
        'witnesses', 'action_taken', 'status', 'resolution',
        'resolved_at', 'notify_guardian', 'commitment',
    ];

    protected $casts = [
        'date' => 'date',
        'resolved_at' => 'datetime',
        'notify_guardian' => 'boolean',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }
}
