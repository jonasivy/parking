<?php

namespace App\Rules\EntryPoint;

use App\Services\SettingService;
use Illuminate\Contracts\Validation\Rule;

class SettingRule implements Rule
{
    /** @var \App\Services\SettingService */
    protected $settingService;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
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

        if (empty($this->settingService->getAxis('x')) || empty($this->settingService->getAxis('y'))) {
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
        return 'The maximum x and y should be set first.';
    }
}
