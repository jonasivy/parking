<?php

namespace App\Http\Requests\Parking;

use App\Services\LogService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class Request extends FormRequest
{
    /** @var \App\Services\LogService */
    protected $logService;

    /** @var \App\Models\Log */
    public $log;

    /**
     * @param \App\Services\LogService $logService
     */
    public function __construct(LogService $logService)
    {
        parent::__construct();
        $this->logService = $logService;

        $this->log = $this->logService->makeRequest($this->fullUrl(), $this->all());
    }

    /**
     * @return boolean
     */
    public function authorize()
    {
        $this->logService = app()->make(LogService::class);
        $this->log = $this->logService->makeRequest($this->fullUrl(), $this->all());

        return true;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $this->log->update([
            'response' => $this->getValidatorInstance()->errors()->messages(),
        ]);

        throw (new ValidationException($validator))
                    ->errorBag($this->errorBag)
                    ->redirectTo($this->getRedirectUrl());
    }
}
