<?php

namespace App\Repositories\Slot;

use App\Models\Slot\Type;
use App\Repositories\Repository;

class TypeRepository extends Repository
{
    /**
     * @param \App\Models\Slot\Type $model
     * @return void
     */
    public function __construct(Type $model)
    {
        parent::__construct($model);
    }

    /**
     * Get one random slot type.
     *
     * @return \App\Models\Slot\Type
     */
    public function getRandomType()
    {
        return $this->refresh()
            ->getOneRandom();
    }
}
