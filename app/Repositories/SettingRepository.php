<?php

namespace App\Repositories;

use App\Models\Setting;

class SettingRepository extends Repository
{
    /** @var \App\Models\Setting */
    protected $model;

    /**
     * @return void
     */
    public function __construct(Setting $model)
    {
        parent::__construct($model);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getXAxis()
    {
        return $this->refresh()
            ->getOne([
                'code' => 'x-axis',
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getYAxis()
    {
        return $this->refresh()
            ->getOne([
                'code' => 'y-axis',
            ]);
    }

    /**
     * @param string $value
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function setXAxis($value)
    {
        return $this->updateOrCreate([
            'code' => 'x-axis',
        ], [
            'value' => $value,
        ]);
    }

    /**
     * @param string $value
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function setYAxis($value)
    {
        return $this->updateOrCreate([
            'code' => 'y-axis',
        ], [
            'value' => $value,
        ]);
    }
}
