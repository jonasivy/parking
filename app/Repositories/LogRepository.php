<?php

namespace App\Repositories;

use App\Models\Log;

class LogRepository extends Repository
{
    /**
     * @return void
     */
    public function __construct(Log $model)
    {
        parent::__construct($model);
    }

    /**
     * Creates a new request.
     *
     * @param string $endpoint
     * @param array $request
     * @return \App\Models\Log
     */
    public function setRequest(string $endpoint, array $request)
    {
        return $this->create([
            'endpoint' => $endpoint,
            'request'  => $request,
        ]);
    }

    /**
     * Set response for request.
     *
     * @param \App\Models\Log $log
     * @param array $response
     * @return \App\Models\Log
     */
    public function setResponse(Log $log, array $response)
    {
        return $log->update([
            'response' => $response,
        ]);
    }
}
