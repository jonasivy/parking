<?php

namespace App\Services;

use App\Models\Log;
use App\Repositories\LogRepository;

class LogService
{
    /** @var \App\Repositories\LogRepository */
    protected $repository;

    /**
     * @param \App\Repositories\LogRepository $repository
     * @return void
     */
    public function __construct(LogRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Creates a new request to park, unpark or cancel transaction.
     *
     * @param string $endpoint
     * @param array $request
     */
    public function makeRequest(string $endpoint, array $request)
    {
        return $this->repository
            ->setRequest($endpoint, $request);
    }

    /**
     * Set response to request.
     *
     * @param \App\Models\Log $log
     * @param array $response
     * @return \App\Models\Log
     */
    public function setResponse(Log $log, array $response)
    {
        $this->repository->setResponse($log, $response);
        
        return $log->fresh();
    }
}
