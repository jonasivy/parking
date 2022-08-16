<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Services\EntryPointService;
use App\Services\ParkingService;
use App\Services\SettingService;
use App\Services\SlotService;
use Database\Seeders\SlotTypeSeeder;
use Database\Seeders\VehicleTypeSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Tests\TestCase;

class ParkingServiceTest extends TestCase
{
    use DatabaseMigrations;

    /** @var int */
    protected $xAxis;

    /** @var int */
    protected $yAxis;

    /** @var \App\Services\EntryPointService */
    protected $entryPointService;

    /** @var \App\Services\ParkingService */
    protected $parkingService;

    /** @var \App\Services\SettingService */
    protected $settingService;

    /** @var \App\Services\SlotService */
    protected $slotService;

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
        $this->entryPointService = app()->make(EntryPointService::class);
        $this->parkingService = app()->make(ParkingService::class);
        $this->settingService = app()->make(SettingService::class);
        $this->slotService = app()->make(SlotService::class);
        $this->xAxis = rand(10, 20);
        $this->yAxis = rand(10, 20);
        $this->settingService->setAxis('x', $this->xAxis);
        $this->settingService->setAxis('y', $this->yAxis);
        $this->slotService->generateMap();
    }

    /**
     * @test
     */
    public function positiveTestParkAVehicle()
    {
        foreach (range(1, $this->xAxis) as $x) {
            $params = [
                'x-axis' => $x,
                'y-axis' => 1,
            ];

            $response = $this->withoutMiddleware()->post(route('entry-point.store'), $params, $this->headers);
            $response->assertStatus(201)
                ->assertJsonFragment([
                    'x-axis' => $x,
                ])
                ->assertJsonFragment([
                    'y-axis' => 1,
                ]);
        }

        $vehicleType = $this->parkingService->getOneRandomType();
        $entryPoint = $this->entryPointService->getOneRandomEntryPoint();

        $params = [
            'txn_id'          => Str::uuid()->toString(),
            'plate_no'        => 'NEW5259',
            'entry_point_id'  => $entryPoint->id,
            'vehicle_type_id' => $vehicleType->id,
        ];
        $response = $this->post(route('parking.store'), $params, $this->headers);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'entry_point' => [
                    'id'     => $entryPoint->id,
                    'x-axis' => $entryPoint->x_axis,
                    'y-axis' => $entryPoint->y_axis,
                ],
                'vehicle_type' => [
                    'id'   => $vehicleType->id,
                    'code' => $vehicleType->code,
                    'name' => $vehicleType->name,
                ],
            ]);

        $response = $this->post(route('parking.store'), $params, $this->headers);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'entry_point' => [
                    'id'     => $entryPoint->id,
                    'x-axis' => $entryPoint->x_axis,
                    'y-axis' => $entryPoint->y_axis,
                ],
                'vehicle_type' => [
                    'id'   => $vehicleType->id,
                    'code' => $vehicleType->code,
                    'name' => $vehicleType->name,
                ],
            ]);
    }

    /**
     * @test
     */
    public function negativeTestParkVehicleWithInvalidTransaction()
    {
        foreach (range(1, $this->xAxis) as $x) {
            $params = [
                'x-axis' => $x,
                'y-axis' => 1,
            ];

            $response = $this->withoutMiddleware()->post(route('entry-point.store'), $params, $this->headers);
            $response->assertStatus(201)
                ->assertJsonFragment([
                    'x-axis' => $x,
                ])
                ->assertJsonFragment([
                    'y-axis' => 1,
                ]);
        }

        $vehicleType = $this->parkingService->getOneRandomType();
        $entryPoint = $this->entryPointService->getOneRandomEntryPoint();

        $params = [
            // 'txn_id'          => Str::uuid()->toString(),
            'plate_no'        => 'NEW5259',
            'entry_point_id'  => $entryPoint->id,
            'vehicle_type_id' => $vehicleType->id,
        ];
        $response = $this->post(route('parking.store'), $params, $this->headers);
        $response->assertStatus(422)
            ->assertJsonFragment([
                'message' => 'The txn id field is required.',
            ])
            ->assertJsonFragment([
                'txn_id' => [
                    'The txn id field is required.',
                ],
            ]);
    }

    /**
     * @test
     */
    public function negativeTestParkVehicleWithInvalidPlateNo()
    {
        foreach (range(1, $this->xAxis) as $x) {
            $params = [
                'x-axis' => $x,
                'y-axis' => 1,
            ];

            $response = $this->withoutMiddleware()->post(route('entry-point.store'), $params, $this->headers);
            $response->assertStatus(201)
                ->assertJsonFragment([
                    'x-axis' => $x,
                ])
                ->assertJsonFragment([
                    'y-axis' => 1,
                ]);
        }

        $vehicleType = $this->parkingService->getOneRandomType();
        $entryPoint = $this->entryPointService->getOneRandomEntryPoint();

        // REQUIRED
        $params = [
            'txn_id'          => Str::uuid()->toString(),
            // 'plate_no'        => 'NEW5259',
            'entry_point_id'  => $entryPoint->id,
            'vehicle_type_id' => $vehicleType->id,
        ];
        $response = $this->post(route('parking.store'), $params, $this->headers);
        $response->assertStatus(422)
            ->assertJsonFragment([
                'plate_no' => [
                    'The plate no field is required.',
                ],
            ]);

        // INVALID FORMAT
        $params = [
            'txn_id'          => Str::uuid()->toString(),
            'plate_no'        => '123456',
            'entry_point_id'  => $entryPoint->id,
            'vehicle_type_id' => $vehicleType->id,
        ];
        $response = $this->post(route('parking.store'), $params, $this->headers);
        $response->assertStatus(422)
            ->assertJsonFragment([
                'plate_no' => [
                    "The plate no {$params['plate_no']} is not valid.",
                ],
            ]);
    }

    /**
     * @test
     */
    public function negativeTestParkVehicleWithInvalidEntryPoint()
    {
        foreach (range(1, $this->xAxis) as $x) {
            $params = [
                'x-axis' => $x,
                'y-axis' => 1,
            ];

            $response = $this->withoutMiddleware()->post(route('entry-point.store'), $params, $this->headers);
            $response->assertStatus(201)
                ->assertJsonFragment([
                    'x-axis' => $x,
                ])
                ->assertJsonFragment([
                    'y-axis' => 1,
                ]);
        }

        $vehicleType = $this->parkingService->getOneRandomType();
        $entryPoint = $this->entryPointService->getOneRandomEntryPoint();

        // REQUIRED
        $params = [
            'txn_id'          => Str::uuid()->toString(),
            'plate_no'        => 'NEW5259',
            // 'entry_point_id'  => $entryPoint->id,
            'vehicle_type_id' => $vehicleType->id,
        ];
        $response = $this->post(route('parking.store'), $params, $this->headers);
        $response->assertStatus(422)
            ->assertJsonFragment([
                'entry_point_id' => [
                    'The entry point id field is required.',
                ],
            ]);

        // INVALID FORMAT
        $params = [
            'txn_id'          => Str::uuid()->toString(),
            'plate_no'        => 'NEW5259',
            'entry_point_id'  => uniqid(),
            'vehicle_type_id' => $vehicleType->id,
        ];
        $response = $this->post(route('parking.store'), $params, $this->headers);
        $response->assertStatus(422)
            ->assertJsonFragment([
                'entry_point_id' => [
                    'The entry points id does not exist.',
                ],
            ]);
    }

    /**
     * @test
     */
    public function negativeTestParkVehicleWithVehicleTypeId()
    {
        foreach (range(1, $this->xAxis) as $x) {
            $params = [
                'x-axis' => $x,
                'y-axis' => 1,
            ];

            $response = $this->withoutMiddleware()->post(route('entry-point.store'), $params, $this->headers);
            $response->assertStatus(201)
                ->assertJsonFragment([
                    'x-axis' => $x,
                ])
                ->assertJsonFragment([
                    'y-axis' => 1,
                ]);
        }

        $vehicleType = $this->parkingService->getOneRandomType();
        $entryPoint = $this->entryPointService->getOneRandomEntryPoint();

        // REQUIRED
        $params = [
            'txn_id'          => Str::uuid()->toString(),
            'plate_no'        => 'NEW5259',
            'entry_point_id'  => $entryPoint->id,
            // 'vehicle_type_id' => $vehicleType->id,
        ];
        $response = $this->post(route('parking.store'), $params, $this->headers);
        $response->assertStatus(422)
            ->assertJsonFragment([
                'vehicle_type_id' => [
                    'The vehicle type id field is required.',
                ],
            ]);

        // INVALID FORMAT
        $params = [
            'txn_id'          => Str::uuid()->toString(),
            'plate_no'        => 'NEW5259',
            'entry_point_id'  => $entryPoint->id,
            'vehicle_type_id' => uniqid(),
        ];
        $response = $this->post(route('parking.store'), $params, $this->headers);
        $response->assertStatus(422)
            ->assertJsonFragment([
                'vehicle_type_id' => [
                    "The vehicle type id {$params['vehicle_type_id']} does not exist.",
                ],
            ]);
    }
}
