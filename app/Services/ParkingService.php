<?php

namespace App\Services;

use App\Models\Log;
use App\Repositories\ParkingRepository;
use App\Repositories\Vehicle\TypeRepository;
use Illuminate\Support\Str;

class ParkingService
{
    /** @var \App\Repositories\ParkingRepository */
    protected $repository;

    /** @var \App\Repositories\Vehicle\TypeRepository */
    protected $vehicleTypeRepository;

    /** @var \App\Services\SlotService */
    protected $slotService;

    /** @var \App\Services\EntryPointService */
    protected $entryPointService;

    /**
     * @param \App\Repositories\ParkingRepository $repository
     * @param \App\Repositories\Vehicle\TypeRepository $typeRepository
     * @param \App\Services\SlotService $slotService
     * @return void
     */
    public function __construct(
        ParkingRepository $repository,
        TypeRepository $vehicleTypeRepository,
        SlotService $slotService,
        EntryPointService $entryPointService
    ) {
        $this->repository = $repository;
        $this->vehicleTypeRepository = $vehicleTypeRepository;
        $this->slotService = $slotService;
        $this->entryPointService = $entryPointService;
    }

    /**
     * Get one transaction by transaction id.
     *
     * @param string $txnId
     * @return \App\Models\Transaction
     */
    public function getOneByTxnId(?string $txnId = null)
    {
        return $this->repository->getOne([
            'txn_id' => $txnId,
        ]);
    }

    /**
     * Park vehicle to the nearest available slot.
     *
     * @param \App\Models\Log $log
     * @return \App\Models\Transaction
     */
    public function park(Log $log)
    {
        $entryPoint = $this->entryPointService->getOneById($log->request['entry_point_id']);
        $slot = $this->slotService->getOneSlotByEntryPointAndVehicleType(
            $entryPoint->x_axis,
            $entryPoint->y_axis,
            $log->request['vehicle_type_id'],
        );
        $initialParkingFee = 40;

        return $this->repository->saveTransaction(
            $log->request['txn_id'],
            Str::uuid()->toString(),
            $log->request['entry_point_id'],
            $slot,
            $log->request['vehicle_type_id'],
            $log->request['plate_no'],
            $initialParkingFee,
            $log->id,
        );
    }

    /**
     * Unpark vehicle.
     */
    public function unpark()
    {
        // TO DO
    }

    /**
     * Get vehicle type by id.
     *
     * @param int $id
     * @return \App\Models\Vehicle\Type
     */
    public function getVehicleTypeById($id)
    {
        return $this->vehicleTypeRepository
            ->getOneById($id);
    }

    /**
     * Get one random vehicle type.
     */
    public function getOneRandomType()
    {
        return $this->vehicleTypeRepository
            ->getOneRandomType();
    }
}
