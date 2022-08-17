<?php

namespace App\Repositories\Vehicle;

use App\Models\Vehicle\Type;
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
     * Get one vehicle type by id
     *
     * @param int $id
     */
    public function getOneById($id)
    {
        return $this->getOne([
            'id' => $id,
        ]);
    }

    /**
     * Get one random slot type.
     *
     * @return \App\Models\Slot\Type
     */
    public function getOneRandomType()
    {
        return $this->refresh()
            ->getOneRandom();
    }
}
