<?php

namespace App\Rules\EntryPoint;

use App\Services\SettingService;
use Illuminate\Contracts\Validation\Rule;

class ValidXAxisRule implements Rule
{
    /** @var \App\Services\SettingService */
    protected $settingService;

    /** @var int */
    protected $xAxis;

    /** @var int */
    protected $yAxis;

    /**
     * Create a new rule instance.
     *
     * @param int $xAxis
     * @return void
     */
    public function __construct(int $yAxis = 0)
    {
        $this->yAxis = $yAxis;
        $this->settingService = app()
            ->make(SettingService::class);
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

        $this->xAxis = $value;

        $minXAxis = 1;
        $minYAxis = 1;
        $maxXAxis = $this->settingService->getAxis('x')->value;
        $maxYAxis = $this->settingService->getAxis('y')->value;

        if (!in_array($value, [
            $minXAxis,
            $maxXAxis,
        ])) {
            if (!in_array($this->yAxis, [
                $minYAxis,
                $maxYAxis,
            ])) {
                $status = false;
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
        return "The entry points x{$this->xAxis}:y{$this->yAxis} is not valid.";
    }
}
