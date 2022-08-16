<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    /** @var string */
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $entryPoint = [
            'id'     => $this->entry_point_id,
            'x-axis' => $this->entryPoint->x_axis,
            'y-axis' => $this->entryPoint->y_axis,
        ];

        $slot = [
            'id'     => $this->slot_id,
            'x-axis' => $this->slot->x_axis,
            'y-axis' => $this->slot->y_axis,
        ];

        $slotType = [
            'id'   => $this->slotType->id,
            'code' => $this->slotType->code,
            'name' => $this->slotType->name,
        ];

        $vehicleType = [
            'id'   => $this->vehicleType->id,
            'code' => $this->vehicleType->code,
            'name' => $this->vehicleType->name,
        ];

        return [
            'txn_id'              => $this->txn_id,
            'txn_ref_id'          => $this->txn_ref_id,
            'plate_no'            => $this->plate_no,
            'initial_parking_fee' => $this->initial_parking_fee,
            'parked_at'           => $this->parked_at,
            'entry_point'         => $entryPoint,
            'slot'                => $slot,
            'slot_type'           => $slotType,
            'vehicle_type'        => $vehicleType,
        ];
    }
}
