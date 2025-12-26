<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'headline',
        'summary',
        'phone',
        'location',
        'website',
        'linkedin',
        'github',
        'driver_license',
        'driver_license_type',
        'template',
    ];

    protected $casts = [
        'driver_license' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
