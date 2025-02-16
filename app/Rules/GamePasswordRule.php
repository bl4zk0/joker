<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;

class GamePasswordRule implements Rule
{
    private $gamePassword;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($gamePassword)
    {
        $this->gamePassword = $gamePassword;
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
        return $value === $this->gamePassword;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return Lang::get('Invalid pin code');
    }
}
