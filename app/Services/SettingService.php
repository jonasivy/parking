<?php

namespace App\Services;

use App\Repositories\SettingRepository;

class SettingService
{
    /** @var \App\Repositories\SettingRepository */
    protected $repository;

    /**
     * @param \App\Repositories\SettingRepository $repository
     * @return void
     */
    public function __construct(SettingRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get config for x or y axis.
     */
    public function getAxis($axis)
    {
        switch ($axis) {
            case 'x':
                    $collection = $this->repository->getXAxis();
                break;
            case 'y':
                    $collection = $this->repository->getYAxis();
                break;
            default:
                $collection = null;
        }

        return $collection;
    }

    /**
     * Set config for x or y axis.
     *
     * @param string $axis
     * @param string $value
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function setAxis($axis, $value)
    {
        switch ($axis) {
            case 'x':
                    $collection = $this->repository->setXAxis($value);
                break;
            case 'y':
                    $collection = $this->repository->setYAxis($value);
                break;
            default:
                $collection = null;
        }

        return $collection;
    }
}
