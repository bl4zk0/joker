<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $guarded = [];
    protected $casts = ['quarter' => 'integer', 'call' => 'integer', 'take' => 'integer', 'result' => 'integer'];
    protected $hidden = ['created_at', 'updated_at'];

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
