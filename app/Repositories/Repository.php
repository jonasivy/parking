<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class Repository
{
    /**
     * @param \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Find resource.
     *
     * @param mixed $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Get resources.
     *
     * @param array $filter
     * @param array $orders
     * @param integer $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get(array $filter, $orders = [], $limit = 100)
    {
        $query = $this->model
            ->remember(config('cache.retention'))
            ->where($filter);

        foreach ($orders as $column => $order) {
            $query->orderBy($column, $order);
        }

        return $query
            ->limit($limit)
            ->get();
    }

    /**
     * Get one resources.
     *
     * @param array $filter
     * @param array $orders
     * @param integer $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOne(array $filter, $orders = [])
    {
        $query = $this->model
            ->remember(config('cache.retention'))
            ->where($filter);

        foreach ($orders as $column => $order) {
            $query->orderBy($column, $order);
        }

        return $query
            ->first();
    }

    /**
     * Create new resource.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return $this->model
            ->create($data);
    }

    /**
     * Update existing resource.
     *
     * @param mixed $id
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update($id, array $data)
    {
        return $this->model
            ->where('id', $id)
            ->update($data);
    }

    /**
     * Delete existing resource.
     *
     * @param mixed $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->model->delete($id)
            ? true
            : false;
    }
}
