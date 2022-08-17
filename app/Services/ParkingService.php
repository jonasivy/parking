<?php

namespace App\Services;

use App\Enums\SlotType;
use App\Models\Log;
use App\Models\Transaction;
use App\Repositories\ParkingRepository;
use App\Repositories\Vehicle\TypeRepository;
use Carbon\Carbon;
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
     * Get one transaction by id.
     *
     * @param int $id
     * @return \App\Models\Transaction
     */
    public function getOneById($id)
    {
        return $this->repository
            ->refresh()
            ->find($id);
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
        if ($exitTransaction = $this->repository->getReEnterTransaction($log->request['plate_no'])) {
            $enterTransaction = $exitTransaction->enter;
            $exitTransaction->delete();
            
            return $enterTransaction;
        }

        $entryPoint = $this->entryPointService->getOneById($log->request['entry_point_id']);
        $slot = $this->slotService->getOneSlotByEntryPointAndVehicleType(
            $entryPoint->x_axis,
            $entryPoint->y_axis,
            $log->request['vehicle_type_id'],
        );
        $initialParkingFee = (float) SlotType::fromKey('INITIAL')->value;

        return $this->repository->saveParkingTransaction(
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
     *
     * @param \App\Models\Log $log
     * @param \App\Models\Transaction $paring
     */
    public function unpark(Log $log, $parking)
    {
        $succeedingFee = $this->getSucceedingFee($parking->parked_at, $parking->slotType);
        $dayFee = $this->getDayFee($parking->parked_at, $parking->slotType);

        return $this->repository
            ->saveUnparkingTransaction($log, $parking, $succeedingFee, $dayFee);
    }

    /**
     * @param
     */
    public function getSucceedingFee($parkedAt, $slotType)
    {
        $fee = (int) SlotType::fromKey(strToUpper($slotType->name))->value;
        $now = Carbon::now();
        $parkedAt = Carbon::parse($parkedAt);
        $diffInMinutes = $parkedAt->diffInMinutes($now->toDateTimeString());
        $hours = $diffInMinutes
            ? ceil($diffInMinutes/60) % 24
            : 0;

        return $hours * $fee;
    }

    /**
     * @param
     */
    public function getDayFee($parkedAt)
    {
        $fee = (int) SlotType::fromKey('DAY')->value;
        $now = Carbon::now();
        $parkedAt = Carbon::parse($parkedAt);
        $diffInMinutes = $parkedAt->diffInMinutes($now->toDateTimeString());
        $days = $diffInMinutes
            ? floor(ceil($diffInMinutes / 60) / 24)
            : 0;

        return $days * $fee;
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
