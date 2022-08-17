<?php

namespace App\Rules\Parking;

use App\Services\ParkingService;
use Illuminate\Contracts\Validation\Rule;

class PlateNoRule implements Rule
{
    /** @var \App\Services\ParkingService */
    protected $parkingService;

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
        
        if ($this->parkingService->checkIfVehicleIsParked($value)) {
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
        $plateNo = request()->input('plate_no');

        return "The :attribute {$plateNo} is already parked.";
    }
}
