<?php

namespace App\Repositories;

use App\Models\Slot;

class SlotRepository extends Repository
{
    /** @var \App\Models\Slot */
    protected $model;

    /**
     * @return void
     */
    public function __construct(Slot $model)
    {
        parent::__construct($model);
    }
}
