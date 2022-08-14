<?php

namespace App\Repositories\Slot;

use App\Models\Slot\Type;
use App\Repositories\Repository;

class TypeRepository extends Repository
{
    /**
     * @return void
     */
    public function __construct(Type $model)
    {
        parent::__construct($model);
    }

    /**
     * @return \App\Models\Slot\Type
     */
    public function getRandomType()
    {
        return $this->refresh()
            ->getOneRandom();
    }
}
