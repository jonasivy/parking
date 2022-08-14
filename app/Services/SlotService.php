<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Slot;
use App\Repositories\SettingRepository;
use App\Repositories\Slot\TypeRepository;
use App\Repositories\SlotRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

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

                $slotType = $this->getOneRandomSlotType();
                $slots[$slotType->code][] = $this->repository->makeSlot($slotType->id, $x, $y);
            }
        }

        return $slots;
    }

    /**
     * Get list of parking slots
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Collection
     */
    public function getSlotList(Request $request)
    {
        return QueryBuilder::for($this->repository->model, $request)
            ->select([
                'id',
                'slot_type_id',
                'x_axis',
                'y_axis',
                DB::raw("ST_Distance_Sphere(POINT(1, 1), POINT(`x_axis`, `y_axis`)) as `distance`"),
                'created_at'
            ])
            ->defaultSort('id')
            ->allowedSorts([
                'id',
                'x_axis',
                'y_axis',
                'distance',
            ])
            ->allowedFilters([
                AllowedFilter::scope('type'),
            ])
            ->with([
                'type' => fn ($query) => $query->remember(config('cache.retention')),
            ])
            ->paginate($request->input('size'));
    }

    /**
     * Get one parking slot by coordinates
     *
     * @param int $x
     * @param int $y
     * @return \App\Models\Slot
     */
    public function getOneByCoordinates($x, $y)
    {
        return $this->repository
            ->getOneByCoordinates($x, $y);
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
     * Get a random parking slot type.
     *
     * @return \App\Models\Slot\Type
     */
    public function getOneRandomSlotType()
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

    /**
     * Get one parking slot type by id.
     *
     * @param int $id
     * @return \App\Models\Slot\Type
     */
    public function getOneTypeById($id)
    {
        return $this->slotTypeRepository
            ->getOne([
                'id' => $id,
            ]);
    }

    /**
     * Get one random slot.
     *
     * @return \App\Models\Slot
     */
    public function getOneRandomSlot()
    {
        return $this->repository
            ->getOneRandom();
    }

    /**
     * Update slot type.
     *
     * @param \App\Models\Slot $slot
     */
    public function changeSlotType(Slot $slot, int $slot_type_id)
    {
        return $this->repository->update($slot->id, [
            'slot_type_id' => $slot_type_id,
        ]);
    }
}
