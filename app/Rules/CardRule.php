<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CardRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return in_array($value, auth()->user()->player->cards, true);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "You've been validated, fool!";
    }
}
