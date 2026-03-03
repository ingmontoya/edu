<?php

namespace App\Models;

use App\Models\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    use BelongsToTenant;
    protected $fillable = ['institution_id', 'name', 'order'];

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }
}
