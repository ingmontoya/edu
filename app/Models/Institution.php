<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends Model
{
    protected $fillable = [
        'name', 'nit', 'dane_code', 'logo', 'address',
        'phone', 'email', 'city', 'department', 'rector_name', 'grading_scale',
    ];

    protected $casts = [
        'grading_scale' => 'array',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function academicYears(): HasMany
    {
        return $this->hasMany(AcademicYear::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function areas(): HasMany
    {
        return $this->hasMany(Area::class);
    }

    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function activeYear()
    {
        return $this->academicYears()->where('is_active', true)->first();
    }
}
