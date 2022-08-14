<?php

namespace App\Http\Resources\Slot;

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
        return [
            'id'         => $this->id,
            'type'       => [
                'id'   => $this->slot_type_id,
                'code' => $this->type->code,
                'name' => $this->type->name,
            ],
            'x-axis'     => $this->x_axis,
            'y-axis'     => $this->y_axis,
            'distance'   => $this->distance,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
