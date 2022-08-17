<?php

namespace App\Rules\Unparking;

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
     * @param string $id
     * @return void
     */
    public function __construct($log)
    {
        $this->parkingRepository = app()->make(ParkingService::class);
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

        if ($transaction = $this->parkingRepository->getOneById($value)) {
            if ($exit = $transaction->exit) {
                $exit = $exit->fresh([
                    'unparkLog' => fn ($query) => $query->remember(config('cache.retention')),
                ]);
                if ($exit->unparked_at) {
                    $this->response = $exit->unparkLog->response;
                    $this->log->update([
                        'response' => $this->response,
                    ]);

                    throw new HttpResponseException(response($this->response, 200));
                }
            }
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
