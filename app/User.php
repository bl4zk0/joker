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
        'username', 'email', 'password', 'email_verified_at', 'socialite_account', 'avatar_url'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email', 'created_at', 'updated_at', 'socialite_account', 'email_verified_at', 'player'
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

    // TODO
    public function getIsAdminAttribute()
    {
        $admins = ['admin@joker.local'];

        return in_array($this->email, $admins);
    }
}
