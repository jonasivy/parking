<?php

namespace App\Repositories;

use App\Models\Slot;

class SlotRepository extends Repository
{
    /**
     * @return void
     */
    public function __construct(Slot $model)
    {
        parent::__construct($model);
    }

    /**
     * Create new slot for parking
     *
     * @param int $slotTypeId
     * @param int $x
     * @param int $y
     * @return \App\Models\Slot
     */
    public function makeSlot(int $slotTypeId, int $x, int $y)
    {
        return $this->create([
            'slot_type_id' => $slotTypeId,
            'x_axis'       => $x,
            'y_axis'       => $y,
        ]);
    }

    /**
     * @param array $types
     */
    public function getCountSummaryPerType(array $types)
    {
        return $this->model
            ->whereHas('type', function ($query) use ($types) {
                return $query->remember(config('cache.retention'))
                    ->whereIn('code', array_map(fn ($value) => strToUpper($value), $types));
            })
            ->get();
    }

    /**
     * @return bool
     */
    public function dataExists()
    {
        return $this->model
            ->exists();
    }
}
