<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected static function boot()
    {
        parent::boot();

        static::created(function($user) {
            Player::create(['user_id' => $user->id]);
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'email_verified_at', 'socialite_account',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'socialite_account' => 'boolean'
    ];

    public function player()
    {
        return $this->hasOne(Player::class);
    }

    public function socialite()
    {
        return $this->hasOne(Socialite::class);
    }

    public function getUsernameAttribute($username)
    {
        return $this->socialite_account ? $this->socialite->name : $username;
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->socialite_account) {
            return $this->socialite->avatar_url;
        } else {
            return 'https://www.gravatar.com/avatar/' . md5($this->email) . '?s=60&d=retro&f=y';
        }
    }
}
