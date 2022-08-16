<?php

namespace App\Rules\Parking;

use Illuminate\Contracts\Validation\Rule;

class PlateNoRule implements Rule
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
        $pattern = '/[A-Za-z]{3}[0-9]{3}/';
        if (empty(preg_match($pattern, $value, $matches))) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $plateNo = request()->input('plate_no');

        return "The :attribute {$plateNo} is not valid.";
    }
}
