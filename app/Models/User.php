<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'profile_photo',
        'password',
        'role',
        'status',
        'is_active',
        'requested_role',
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
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $user->listings()->get()->each->delete();
        });
    }

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    public function marketingSales()
    {
        return $this->hasMany(MarketingSale::class);
    }

    public function marketingTargets()
    {
        return $this->hasMany(MarketingTarget::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

        public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
