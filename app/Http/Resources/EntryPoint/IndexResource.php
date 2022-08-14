<?php

namespace App\Http\Resources\EntryPoint;

use Illuminate\Http\Resources\Json\JsonResource;

class IndexResource extends JsonResource
{
    /** @var string */
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'x-axis'     => $this->x_axis,
            'y-axis'     => $this->y_axis,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
