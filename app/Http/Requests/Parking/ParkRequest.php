<?php

namespace App\Http\Requests\Parking;

use App\Rules\EntryPoint\ExistRule;
use App\Rules\Parking\PlateNoFormatRule;
use App\Rules\Parking\PlateNoRule;
use App\Rules\Parking\TransactionRule;
use App\Rules\VehicleType\ExistRule as VehicleTypeExistRule;

class ParkRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'txn_id' => [
                'required',
                new TransactionRule($this->log),
            ],
            'entry_point_id' => [
                'required',
                new ExistRule(),
            ],
            'plate_no' => [
                'required',
                new PlateNoRule(),
                new PlateNoFormatRule(),
            ],
            'vehicle_type_id' => [
                'required',
                new VehicleTypeExistRule(),
            ],
        ];
    }
}
