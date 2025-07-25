<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_guest' => 'boolean',
        'guest_expires_at' => 'datetime',
    ];

    public function genres()
    {
        return $this->hasMany(Genre::class);
    }

    public function hashTags()
    {
        return $this->hasMany(HashTag::class);
    }

    /**
     * ゲストユーザーかどうかを判定
     */
    public function isGuest(): bool
    {
        return $this->is_guest;
    }

    /**
     * ゲストアカウントが有効期限切れかどうかを判定
     */
    public function isGuestExpired(): bool
    {
        if (!$this->is_guest || !$this->guest_expires_at) {
            return false;
        }

        return now()->isAfter($this->guest_expires_at);
    }

    /**
     * ゲストアカウントを本登録用に変換
     */
    public function convertFromGuest(array $userData): void
    {
        $this->update([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => $userData['password'],
            'is_guest' => false,
            'guest_expires_at' => null,
            'guest_session_id' => null,
        ]);
    }
}
