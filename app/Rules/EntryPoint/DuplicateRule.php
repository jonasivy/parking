<?php

namespace App\Rules\EntryPoint;

use App\Services\EntryPointService;
use Illuminate\Contracts\Validation\Rule;

class DuplicateRule implements Rule
{
    /** @var \App\Services\EntryPointService */
    protected $entryPointRepository;

    /** @var int */
    protected $yAxis;

    /**
     * Create a new rule instance.
     *
     * @param int $yAxis
     * @return void
     */
    public function __construct($yAxis)
    {
        $this->yAxis = $yAxis;
        $this->entryPointRepository = app()
            ->make(EntryPointService::class);
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
        $this->xAxis = $value;
        $status = true;

        if ($this->entryPointRepository->isExists($value, $this->yAxis)) {
            $status = false;
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
        return "The entry points x{$this->xAxis}:y{$this->yAxis} already exists.";
    }
}
