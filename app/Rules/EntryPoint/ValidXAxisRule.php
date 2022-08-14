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

        $minAxisX = 1;
        $minAxisY = 1;
        $maxAxisX = $this->settingService->getAxis('x')->value ?? 0;
        $maxAxisY = $this->settingService->getAxis('y')->value ?? 0;

        if (!in_array($value, [
            $minAxisX,
            $maxAxisX,
        ])) {
            if (!in_array($this->yAxis, [
                $minAxisY,
                $maxAxisY,
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
