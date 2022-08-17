<?php

namespace App\Rules\VehicleType;

use App\Services\ParkingService;
use Illuminate\Contracts\Validation\Rule;

class ExistRule implements Rule
{
    /** @var \App\Services\ParkingService */
    protected $parkingService;

    /** @var string */
    protected $value;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->parkingService = app()->make(ParkingService::class);
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

        if (empty($this->parkingService->getVehicleTypeById($value))) {
            $this->value = $value;
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
        return "The vehicle type id {$this->value} does not exist.";
    }
}
