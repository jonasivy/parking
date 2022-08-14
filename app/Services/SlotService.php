<?php

namespace App\Services;

use App\Repositories\SlotRepository;

class SlotService
{
    /** @var \App\Repositories\SlotRepository */
    protected $repository;

    /**
     * @param \App\Repositories\SlotRepository $repository
     * @return void
     */
    public function __construct(SlotRepository $repository)
    {
        $this->repository = $repository;
    }
}
