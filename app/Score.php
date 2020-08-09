<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $guarded = [];
    protected $casts = ['call' => 'integer'];

    public $position = null;

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function getPositionAttribute()
    {
        return $this->player->position;
    }
}
