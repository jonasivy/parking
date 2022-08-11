<?php

namespace App\Repositories;

use App\Models\EntryPoint;

class EntryPointRepository extends Repository
{
    /** @var \App\Models\EntryPoint */
    protected $model;

    /**
     * @return void
     */
    public function __construct(EntryPoint $model)
    {
        $this->model = $model;
    }
}
