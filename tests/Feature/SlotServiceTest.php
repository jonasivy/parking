<?php

namespace Tests\Feature;

use App\Services\SettingService;
use App\Services\SlotService;
use Database\Seeders\SlotTypeSeeder;
use Database\Seeders\VehicleTypeSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SlotServiceTest extends TestCase
{
    use DatabaseMigrations;

    /** @var int */
    protected $xAxis;

    /** @var int */
    protected $yAxis;

    /** @var \App\Services\SettingService */
    protected $settingService;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->headers = [
            'accept' => 'application/json',
        ];

        $this->seed(VehicleTypeSeeder::class);
        $this->seed(SlotTypeSeeder::class);
        $this->settingService = app()->make(SettingService::class);
        $this->slotService = app()->make(SlotService::class);
        $this->xAxis = rand(10, 100);
        $this->yAxis = rand(10, 100);
        $this->settingService->setAxis('x', $this->xAxis);
        $this->settingService->setAxis('y', $this->yAxis);
    }

    /**
     * @test
     */
    public function positiveTestShowSlotType()
    {
        $this->slotService->generateMap();

        $slot = $this->slotService->getOneRandomSlot();

        $url = route('slot.show', [
            'x' => $slot->x_axis,
            'y' => $slot->y_axis,
        ]);
        $response = $this->get($url, $this->headers);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'x-axis' => $slot->x_axis,
                'y-axis' => $slot->y_axis,
            ]);
    }


    /**
     * @test
     */
    public function positiveTestUpdateSlotType()
    {
        $this->slotService->generateMap();

        $slot = $this->slotService->getOneRandomSlot();
        $slotType = $this->slotService->getOneRandomSlotType();

        $params = [
            'slot_type_id' => $slotType->id,
        ];
        $url = route('slot.update', [
            'x' => $slot->x_axis,
            'y' => $slot->y_axis,
        ]);
        $response = $this->patch("{$url}?". http_build_query($params), [], $this->headers);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'x-axis' => $slot->x_axis,
                'y-axis' => $slot->y_axis,
            ])
            ->assertJsonFragment([
                'id'   => $slotType->id,
                'code' => $slotType->code,
                'name' => $slotType->name,
            ]);
    }

    /**
     * @test
     */
    public function negativeTestUpdateNotExisting()
    {
        $this->slotService->generateMap();

        $slotType = $this->slotService->getOneRandomSlotType();

        $params = [
            'slot_type_id' => $slotType->id,
        ];
        $url = route('slot.update', [
            'x' => uniqid(),
            'y' => uniqid(),
        ]);
        $response = $this->patch("{$url}?". http_build_query($params), [], $this->headers);
        $response->assertStatus(404)
            ->assertJsonFragment([
                'message' => 'Record not found.',
            ]);
    }

    /**
     * @test
     */
    public function negativeTestUpdateWithInvalidSlotType()
    {
        $this->slotService->generateMap();

        $slot = $this->slotService->getOneRandomSlot();

        $params = [
            'slot_type_id' => uniqid(),
        ];
        $url = route('slot.update', [
            'x' => $slot->x_axis,
            'y' => $slot->y_axis,
        ]);
        $response = $this->patch("{$url}?". http_build_query($params), [], $this->headers);
        $response->assertStatus(422)
            ->assertJsonFragment([
                'message' => 'The slot type id does not exists.',
            ]);
    }
}
