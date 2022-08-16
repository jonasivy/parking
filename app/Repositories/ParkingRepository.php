<?php

namespace App\Repositories;

use App\Models\Slot;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ParkingRepository extends Repository
{
    /**
     * @return void
     */
    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }

    /**
     * Save parking as transaction.
     *
     * @param string $txnId
     * @param string $txnRefId
     * @param int $entryPointId
     * @param \App\Models\Slot $slot
     * @param int $vehicleTypeId
     * @param string $plateNo
     * @param float $initialParkingFee
     * @param int $parkedLogId
     */
    public function saveTransaction(
        string $txnId,
        string $txnRefId,
        int $entryPointId,
        Slot $slot,
        int $vehicleTypeId,
        string $plateNo,
        float $initialParkingFee,
        int $parkedLogId,
    ) {
        return $this->firstOrCreate([
            'txn_id'              => $txnId,
            'txn_ref_id'          => $txnRefId,
        ], [
            'entry_point_id'      => $entryPointId,
            'slot_id'             => $slot->id,
            'slot_type_id'        => $slot->slot_type_id,
            'vehicle_type_id'     => $vehicleTypeId,
            'plate_no'            => $plateNo,
            'initial_parking_fee' => $initialParkingFee,
            'parked_log_id'       => $parkedLogId,
            'parked_at'           => Carbon::now()->toDateTimeString(),
        ]);
    }
}
