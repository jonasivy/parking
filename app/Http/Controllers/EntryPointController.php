<?php

namespace App\Http\Controllers;

use App\Http\Requests\EntryPoint\StoreRequest;
use App\Http\Resources\EntryPoint\IndexCollection;
use App\Http\Resources\EntryPoint\IndexResource;
use App\Models\EntryPoint;
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
            $entryPoints = $this->entryPointService
                ->getAllEntryPoints();

            return new IndexCollection($entryPoints);
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
            $entryPoint = $this->entryPointService
                ->makeEntryPoints(
                    $request->input('x-axis'),
                    $request->input('y-axis')
                );

            return new IndexResource($entryPoint);
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
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\EntryPoint $entryPoint
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, EntryPoint $entryPoint)
    {
        try {
            return new IndexResource($entryPoint);
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
     * @param \App\Models\EntryPoint $entryPoint
     * @return \Illuminate\Http\Response
     */
    public function destroy(EntryPoint $entryPoint)
    {
        try {
            $this->entryPointService->removeEntryPoint($entryPoint);

            return response()
                ->json([
                    'message' => 'Deleted',
                ]);
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
