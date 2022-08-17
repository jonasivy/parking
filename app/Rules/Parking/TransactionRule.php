<?php

namespace App\Rules\Parking;

use App\Services\ParkingService;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransactionRule implements Rule
{
    /** @var \App\Services\ParkingService */
    protected $parkingService;

    /** @var \App\Models\Log */
    protected $log;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($log)
    {
        $this->parkingService = app()->make(ParkingService::class);
        $this->log = $log;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $status = true;

        if ($transaction = $this->parkingService->getOneByTxnId($value)) {
            $status = false;
            $this->response = $transaction->parkLog->response;
            $this->log->update([
                'response' => $this->response,
            ]);

            throw new HttpResponseException(response($this->response, 200));
        }

        return $status;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->response;
    }
}
