<?php

namespace App\Rules\Tile\Park;

use Illuminate\Contracts\Validation\Rule;

class ValidYAxisRule implements Rule
{
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

        $x = 1;
        $maxYAxis = $this->settingService->getAxis('y')->value;
        for ($i = 1; $i <= $maxYAxis; $i++) {
            $yRoadTile = ($x + ($i * 3));
            if ($value == $yRoadTile) {
                $status = false;
                break;
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
        return 'The validation error message.';
    }
}
