<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $guarded = [];
    protected $with = ['user:id,name', 'scores'];
    protected $hidden = ['cards'];
    protected $casts = [
        'cards' => 'array',
        'card' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function setPosition($position)
    {
        $this->update(['position' => $position]);
    }

}
