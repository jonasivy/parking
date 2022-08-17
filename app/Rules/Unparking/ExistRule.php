<?php

namespace App\Rules\Unparking;

use App\Models\Transaction;
use App\Services\ParkingService;
use Illuminate\Contracts\Validation\Rule;

class ExistRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @param string $id
     * @return void
     */
    public function __construct()
    {
        $this->parkingRepository = app()->make(ParkingService::class);
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
        $status = true;

        
        if (empty($this->parkingRepository->getOneById($value))) {
            $status = false;
        }

        return $status;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The parking entry transaction not exists.';
    }
}
