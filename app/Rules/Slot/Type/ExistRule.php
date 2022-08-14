<?php

namespace App\Rules\Slot\Type;

use App\Services\SlotService;
use Illuminate\Contracts\Validation\Rule;

class ExistRule implements Rule
{
    /** @param \App\Services\SlotService */
    protected $slotService;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->slotService = app()
            ->make(SlotService::class);
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

        if (empty($this->slotService->getOneTypeById($value))) {
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
        return 'The :attribute does not exists.';
    }
}
