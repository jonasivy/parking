<?php

namespace App\Services;

use App\Models\EntryPoint;
use App\Repositories\EntryPointRepository;

class EntryPointService
{
    /** @var \App\Repositories\EntryPointRepository */
    protected $repository;

    /**
     * @param \App\Repositories\EntryPointRepository $repository
     * @return void
     */
    public function __construct(EntryPointRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get on entry point by id.
     *
     * @param int $id
     * @return \App\Models\EntryPoint
     */
    public function getOneById($id)
    {
        return $this->repository
            ->getOne([
                'id' => $id,
            ]);
    }

    /**
     * Get all saved entry point.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllEntryPoints()
    {
        return $this->repository
            ->getAllEntryPoints();
    }

    /**
     * Get one entry point by id.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEntryPointById($id)
    {
        return $this->repository
            ->getEntryPointById($id);
    }

    /**
     * Check entry point if already existing.
     *
     * @param int $x
     * @param int $y
     */
    public function isExists(int $x, int $y)
    {
        return $this->repository
            ->isExists($x, $y);
    }

    /**
     * Create new entry point.
     *
     * @param int $xAxis
     * @param int $yAxis
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function makeEntryPoints(int $x, int $y)
    {
        return $this->repository
            ->saveEntryPoint($x, $y);
    }

    /**
     * Delete any existing entry point.
     *
     * @param \App\Models\EntryPoint $entryPoint
     * @return bool
     */
    public function removeEntryPoint(EntryPoint $entryPoint)
    {
        return $this->repository
            ->removeEntryPoint($entryPoint);
    }

    /**
     * Get one random entry point.
     *
     * @return \App\Models\EntryPoint
     */
    public function getOneRandomEntryPoint()
    {
        return $this->repository
            ->getOneRandomEntryPoint();
    }
}
