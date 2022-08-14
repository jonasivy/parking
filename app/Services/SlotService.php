<?php

namespace App\Services;

use App\Models\Setting;
use App\Repositories\SettingRepository;
use App\Repositories\Slot\TypeRepository;
use App\Repositories\SlotRepository;

class SlotService
{
    /** @var \App\Repositories\SlotRepository */
    protected $repository;

    /** @var \App\Repositories\Slot\TypeRepository */
    protected $slotTypeRepository;

    /** @var \App\Repositories\SettingRepository */
    protected $settingRepository;

    /**
     * @param \App\Repositories\SlotRepository $repository
     * @param \App\Repositories\Slot\TypeRepository $typeRepository
     * @param \App\Repositories\SettingRepository $settingRepository
     * @return void
     */
    public function __construct(
        SlotRepository $repository,
        TypeRepository $slotTypeRepository,
        SettingRepository $settingRepository
    ) {
        $this->repository = $repository;
        $this->slotTypeRepository = $slotTypeRepository;
        $this->settingRepository = $settingRepository;
    }

    /**
     * Generate slots based on map size x and y axis.
     *
     * @return array
     */
    public function generateMap()
    {
        $maxXAxis = $this->settingRepository->getXAxis();
        $maxYAxis = $this->settingRepository->getYAxis();

        foreach (range(1, $maxYAxis->value) as $y) {
            // SKIP IF COORDINATE IS ROAD, TOP AND BOTTOM ROAD
            if (1 == $y || $maxYAxis->value == $y) {
                continue;
            }

            // SKIP IF COORDINATE IS ROAD, MIDDLE ROAD
            if (!$this->isValidYAxis($maxYAxis, $y)) {
                continue;
            }

            foreach (range(1, $maxXAxis->value) as $x) {
                // SKIP IF COORDINATE IS ROAD, LEFT AND RIGHT ROAD
                if (1 == $x || $maxXAxis->value == $x) {
                    continue;
                }

                $slotType = $this->getRandomSlotType();
                $slots[$slotType->code][] = $this->repository->makeSlot($slotType->id, $x, $y);
            }
        }

        return $slots;
    }

    /**
     * Check if passed y axis is valid
     *
     * @param \App\Models\Setting $maxYAxis
     * @param int $yAxis
     */
    public function isValidYAxis(Setting $maxYAxis, int $yAxis)
    {
        for ($i = 1; $i <= $maxYAxis->value; $i++) {
            $yRoadTile = (1 + ($i * 3));
            if ($yAxis == $yRoadTile) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get a random slot type
     *
     * @return \App\Models\Slot\Type
     */
    public function getRandomSlotType()
    {
        return $this->slotTypeRepository
            ->getRandomType();
    }

    /**
     * Get count of parking slot per type.
     *
     * @param string $types
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCountByType(string $types = 's,m,l')
    {
        $codes = explode(',', $types);

        return $this->repository
            ->getCountSummaryPerType($codes);
    }

    /**
     * Check if slots table has existing data
     *
     * @return bool
     */
    public function dataExists()
    {
        return $this->repository->dataExists();
    }
}
