<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PasswordMatch implements Rule
{
    public function passes($attribute, $value)
    {
        return $value === request('password_confirmation');
    }
    
    public function message()
    {
        return 'The password confirmation does not match.';
    }
}
