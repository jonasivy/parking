<?php

namespace App\Rules\Slot;

use App\Services\SlotService;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ExistRule implements Rule
{
    /** @param \App\Services\SlotService */
    protected $slotService;

    /** @var int */
    protected $x;

    /** @var int */
    protected $y;

    /**
     * Create a new rule instance.
     *
     * @param int $x
     * @param int $y
     * @return void
     */
    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
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

        if (empty($this->slotService->getOneByCoordinates($this->x, $this->y))) {
            throw new ModelNotFoundException();
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
        return 'The validation error message. (Not Used)';
    }
}
