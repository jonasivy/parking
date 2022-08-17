<?php

namespace App\Rules\EntryPoint;

use App\Services\EntryPointService;
use Illuminate\Contracts\Validation\Rule;

class ExistRule implements Rule
{
    /** @var \App\Services\EntryPointService */
    protected $entryPointRepository;

    /**
     * Create a new rule instance.
     *
     * @param int $yAxis
     * @return void
     */
    public function __construct($yAxis = '')
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

        if (empty($this->entryPointRepository->getEntryPointById($value))) {
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
        $message = !empty($this->xAxis) && !empty($this->yAxis)
            ? "x{$this->xAxis}:y{$this->yAxis}"
            : "id";

        return "The entry points {$message} does not exist.";
    }
}
