<?php

namespace App\Repositories;

use App\Models\EntryPoint;

class EntryPointRepository extends Repository
{
    /**
     * @param \App\Models\EntryPoint $model
     * @return void
     */
    public function __construct(EntryPoint $model)
    {
        $this->model = $model;
    }

    /**
     * Get one resources.
     *
     * @param int $x
     * @param int $y
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function isExists(int $x, int $y)
    {
        return $this->model
            ->refresh()
            ->remember(config('cache.retention'))
            ->where([
                'x_axis' => $x,
                'y_axis' => $y,
            ])
            ->exists();
    }

    /**
     * Get all entry points.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllEntryPoints()
    {
        return $this->get([]);
    }

    /**
     * Get one entry points.
     *
     * @param int $id
     */
    public function getEntryPointById($id)
    {
        return $this->getOne([
            'id' => $id,
        ]);
    }

    /**
     * Save new entry point to database.
     *
     * @param int $x
     * @param int $y
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function saveEntryPoint(int $x, int $y)
    {
        return $this->create([
            'x_axis' => $x,
            'y_axis' => $y,
        ]);
    }

    /**
     * Remove and existing entry point
     *
     * @param \App\Models\EntryPoint $entryPoint
     * @return bool
     */
    public function removeEntryPoint(EntryPoint $entryPoint)
    {
        return $this->delete($entryPoint->id);
    }

    /**
     * Get one random entry point.
     *
     * @return \App\Models\EntryPoint
     */
    public function getOneRandomEntryPoint()
    {
        return $this->getOneRandom();
    }
}
