<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class KickUserRule implements Rule
{
    private $players;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($players)
    {
        $this->players = $players;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->players->has($value) && $value != auth()->user()->player->position;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Error';
    }
}
