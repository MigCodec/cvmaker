<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Cv;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function educations()
    {
        return $this->hasMany(Education::class, 'user_id');
    }

    public function skills()
    {
        return $this->hasMany(Skill::class, 'user_id')->orderBy('order');
    }

    public function languages()
    {
        return $this->hasMany(Language::class, 'user_id')->orderBy('order');
    }

    public function cvs()
    {
        return $this->hasMany(Cv::class);
    }
}
