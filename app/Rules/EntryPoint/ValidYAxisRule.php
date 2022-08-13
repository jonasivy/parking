<?php

namespace App\Rules\EntryPoint;

use App\Services\SettingService;
use Illuminate\Contracts\Validation\Rule;

class ValidYAxisRule implements Rule
{
    /** @var \App\Services\SettingService */
    protected $settingService;

    /** @var int */
    protected $yAxis;

    /** @var int */
    protected $xAxis;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $xAxis = 0)
    {
        $this->xAxis = $xAxis;
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

        $this->yAxis = $value;

        $minAxisX = 1;
        $minAxisY = 1;
        $maxAxisX = $this->settingService->getAxis('x')->value;
        $maxAxisY = $this->settingService->getAxis('y')->value;

        if (!in_array($value, [
            $minAxisY,
            $maxAxisY,
        ])) {
            if (!in_array($this->xAxis, [
                $minAxisX,
                $maxAxisX,
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
