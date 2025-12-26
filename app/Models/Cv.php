<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cv extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'target_role',
        'summary',
        'template',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function experiences(): BelongsToMany
    {
        return $this->belongsToMany(Experience::class)->withTimestamps();
    }

    public function educations(): BelongsToMany
    {
        return $this->belongsToMany(Education::class)->withTimestamps();
    }

    public function certificates(): BelongsToMany
    {
        return $this->belongsToMany(Certificate::class)->withTimestamps();
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class)->withTimestamps()->orderBy('order');
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class)->withTimestamps()->orderBy('order');
    }
}
