<?php

namespace App\Http\Controllers;

use App\Http\Requests\Parking\ParkRequest;
use App\Http\Resources\Transaction\Resource;
use App\Services\LogService;
use App\Services\ParkingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ParkingController extends Controller
{
    /** @var \App\Services\ParkingService */
    protected $parkingService;

    /** @var \App\Services\LogService */
    protected $logService;

    /**
     * @return void
     */
    public function __construct(ParkingService $parkingService, LogService $logService)
    {
        $this->parkingService = $parkingService;
        $this->logService = $logService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            // TO DO
        } catch (\Exception $e) {
            Log::error(__CLASS__);
            Log::error(__FUNCTION__);
            Log::error($e);

            return response()
                ->json([
                    'status_code' => 0,
                    'message'     => 'Ooops! Something went wrong!',
                ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ParkRequest $request)
    {
        try {
            $response = DB::transaction(function () use ($request) {
                $transaction = $this->parkingService->park($request->log);

                $response = new Resource($transaction->fresh([
                    'entryPoint'  => fn ($query) => $query->remember(config('cache.retention')),
                    'slot'        => fn ($query) => $query->remember(config('cache.retention')),
                    'slotType'    => fn ($query) => $query->remember(config('cache.retention')),
                    'vehicleType' => fn ($query) => $query->remember(config('cache.retention')),
                ]));

                $this->logService->setResponse($request->log, $response->toArray($request));

                return $response;
            });

            return $response;
        } catch (\Exception $e) {
            Log::error(__CLASS__);
            Log::error(__FUNCTION__);
            Log::error($e);

            return response()
                ->json([
                    'status_code' => 0,
                    'message'     => 'Ooops! Something went wrong!',
                ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            // TO DO
        } catch (\Exception $e) {
            Log::error(__CLASS__);
            Log::error(__FUNCTION__);
            Log::error($e);

            return response()
                ->json([
                    'status_code' => 0,
                    'message'     => 'Ooops! Something went wrong!',
                ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            // TO DO
        } catch (\Exception $e) {
            Log::error(__CLASS__);
            Log::error(__FUNCTION__);
            Log::error($e);

            return response()
                ->json([
                    'status_code' => 0,
                    'message'     => 'Ooops! Something went wrong!',
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // TO DO
        } catch (\Exception $e) {
            Log::error(__CLASS__);
            Log::error(__FUNCTION__);
            Log::error($e);

            return response()
                ->json([
                    'status_code' => 0,
                    'message'     => 'Ooops! Something went wrong!',
                ]);
        }
    }
}
