<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'institution_id', 'year', 'start_date', 'end_date', 'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    protected $appends = ['name'];

    public function getNameAttribute(): string
    {
        return (string) $this->year;
    }

    public function periods(): HasMany
    {
        return $this->hasMany(Period::class)->orderBy('number');
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }
}
