<?php

namespace App\Http\Controllers;

use App\Http\Requests\EntryPoint\StoreRequest;
use App\Services\EntryPointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EntryPointController extends Controller
{
    /** @var \App\Services\EntryPointService */
    protected $entryPointService;

    /**
     * @param \App\Services\EntryPointService $entryPointService
     */
    public function __construct(EntryPointService $entryPointService)
    {
        $this->entryPointService = $entryPointService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return $this->entryPointService
                ->getAllEntryPoints();
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
     * @param  \App\Http\Requests\EntryPoint\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        try {
            return $this->entryPointService
                ->makeEntryPoints(
                    $request->input('x-axis'),
                    $request->input('y-axis')
                );
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
