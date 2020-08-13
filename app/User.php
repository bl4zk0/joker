<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $appends = ['username', 'avatar_url'];

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
        'username', 'email', 'password', 'email_verified_at', 'socialite_account', 'avatar_url'
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

    public function getAvatarUrlAttribute($avatar_url)
    {
        return $this->socialite_account ? $this->socialite->avatar_url : $avatar_url;
    }
}
