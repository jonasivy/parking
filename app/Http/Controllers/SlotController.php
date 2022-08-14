<?php

namespace App\Http\Controllers;

use App\Http\Requests\Slot\ShowRequest;
use App\Http\Requests\Slot\UpdateRequest;
use App\Http\Resources\Slot\Collection;
use App\Http\Resources\Slot\Resource;
use App\Services\SlotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SlotController extends Controller
{
    /** @var \App\Services\SlotService */
    protected $slotService;

    /**
     * @param \App\Services\SlotService $slotService
     * @return void
     */
    public function __construct(SlotService $slotService)
    {
        $this->slotService = $slotService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $slot = $this->slotService->getSlotList($request);

            return new Collection($slot);
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
     * @param \App\Http\Requests\Slot\ShowRequest $request
     * @return \App\Http\Resources\Slot\Resource
     */
    public function show(ShowRequest $request): Resource
    {
        try {
            $slot = $this->slotService->getOneByCoordinates($request->route('x'), $request->route('y'));

            return new Resource($slot);
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
     * @param  \App\Http\Requests\Slot\UpdateRequest  $request
     * @param int $x
     * @param int $y
     * @return \App\Http\Resources\Slot\Resource
     */
    public function update(UpdateRequest $request, int $x, int $y): Resource
    {
        try {
            $slot = $this->slotService->getOneByCoordinates($x, $y);

            $this->slotService->changeSlotType($slot, $request->input('slot_type_id'));
    
            return new Resource($slot->fresh());
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
