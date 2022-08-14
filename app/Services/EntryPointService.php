<?php

namespace App\Services;

use App\Models\EntryPoint;
use App\Repositories\EntryPointRepository;

class EntryPointService
{
    /** @var \App\Repositories\EntryPointRepository */
    protected $entryPointRepository;

    /**
     * @param \App\Repositories\EntryPointRepository $repository
     * @return void
     */
    public function __construct(EntryPointRepository $repository)
    {
        $this->entryPointRepository = $repository;
    }

    /**
     * Get all saved entry point.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllEntryPoints()
    {
        return $this->entryPointRepository
            ->getAllEntryPoints();
    }

    /**
     * Get one entry point by id.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getEntryPointById(int $id)
    {
        return $this->entryPointRepository
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
        return $this->entryPointRepository
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
        return $this->entryPointRepository
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
        return $this->entryPointRepository
            ->removeEntryPoint($entryPoint);
    }
}
