<?php

namespace App\Repositories;

use App\Enums\TransactionType;
use App\Models\Log;
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
    public function saveParkingTransaction(
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
            'txn_id'                 => $txnId,
            'txn_ref_id'             => $txnRefId,
        ], [
            'type'                   => TransactionType::fromKey('ENTER')->value,
            'entry_point_id'         => $entryPointId,
            'slot_id'                => $slot->id,
            'slot_type_id'           => $slot->slot_type_id,
            'vehicle_type_id'        => $vehicleTypeId,
            'plate_no'               => $plateNo,
            'initial_parking_fee'    => $initialParkingFee,
            'succeeding_parking_fee' => 0,
            'day_fee'                => 0,
            'parked_log_id'          => $parkedLogId,
            'parked_at'              => Carbon::now()->toDateTimeString(),
        ]);
    }

    /**
     * Check and get if this park only re-entered parking.
     */
    public function getReEnterTransaction($plateNo)
    {
        $reEnterDateCondition = Carbon::now()
            ->addMinutes(config('app.parking.exit-grace'));

        return $this->model
            ->refresh()
            ->where('type', TransactionType::fromKey('EXIT')->value)
            ->where('plate_no', $plateNo)
            ->where('unparked_at', '>=', $reEnterDateCondition->toDateTimeString())
            ->first();
    }

    /**
     * Save exit parking transaction.
     *
     * @param \App\Models\Log $log
     * @param \App\Models\Transaction $parking
     * @param float $succeedingFee
     * @param float $dayFee
     * @return \App\Models\Transaction
     */
    public function saveUnparkingTransaction(Log $log, Transaction $parking, float $succeedingFee, float $dayFee)
    {
        return $this->firstOrCreate([
            'txn_id'                 => $parking->txn_id . '_t',
            'txn_ref_id'             => $parking->txn_ref_id,
        ], [
            'type'                   => TransactionType::fromKey('EXIT')->value,
            'entry_point_id'         => $parking->entry_point_id,
            'slot_id'                => $parking->slot_id,
            'slot_type_id'           => $parking->slot_type_id,
            'vehicle_type_id'        => $parking->vehicle_type_id,
            'plate_no'               => $parking->plate_no,
            'initial_parking_fee'    => 0,
            'succeeding_parking_fee' => $succeedingFee,
            'day_fee'                => $dayFee,
            'parked_log_id'          => $parking->parked_log_id,
            'parked_at'              => $parking->parked_at,
            'unparked_log_id'        => $log->id,
            'unparked_at'            => Carbon::now()->toDateTimeString(),
            'status_flag'            => true,
        ]);
    }

    /**
     * Update status by reference id.
     *
     * @param string $txn_ref_id
     * @return bool
     */
    public function updateStatusByTransRefId(string $txnRefId)
    {
        $txns = $this->get([
            'txn_ref_id' => $txnRefId,
        ]);

        foreach ($txns as $txn) {
            $txn->update([
                'status_flag' => true,
            ]);
        }
    }
}
