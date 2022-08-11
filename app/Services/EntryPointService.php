<?php

namespace App\Services;

use App\Models\EntryPoint;
use App\Repositories\EntryPointRepository;

class EntryPointService
{
    /** @var \App\Repositories\EntryPointRepository */
    protected $entryPointRepository;

    /**
     * @var EntryPoint $entryPoint
     */
    protected $entryPoint;

    public function __construct(EntryPointRepository $entryPointRepository)
    {
        $this->entryPointRepository = $entryPointRepository;
    }
}
