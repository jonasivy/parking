<?php

namespace Tests\Feature;

use App\Services\SettingService;
use App\Services\SlotService;
use Database\Seeders\SlotTypeSeeder;
use Database\Seeders\VehicleTypeSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GenerateMapTest extends TestCase
{
    use DatabaseMigrations;

    /** @var \App\Services\SlotService */
    protected $slotService;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(VehicleTypeSeeder::class);
        $this->seed(SlotTypeSeeder::class);

        $this->slotService = app()->make(SlotService::class);
        $this->settingService = app()->make(SettingService::class);
    }

    /**
     * @test
     */
    public function positiveGenerateMapTest20By20()
    {
        $this->xAxis = 20;
        $this->yAxis = 20;
        $this->settingService->setAxis('x', $this->xAxis);
        $this->settingService->setAxis('y', $this->yAxis);

        $this->slotService->generateMap();

        $count = $this->slotService->getCountByType('s,m,l')->count();
        $this->assertEquals($count, 216);
    }

    /**
     * @test
     */
    public function positiveGenerateMapTest40By40()
    {
        $this->xAxis = 40;
        $this->yAxis = 40;
        $this->settingService->setAxis('x', $this->xAxis);
        $this->settingService->setAxis('y', $this->yAxis);

        $this->slotService->generateMap();

        $count = $this->slotService->getCountByType('s,m,l')->count();
        $this->assertEquals($count, 988);
    }

    /**
     * @test
     */
    public function positiveGenerateMapTest60By60()
    {
        $this->xAxis = 60;
        $this->yAxis = 60;
        $this->settingService->setAxis('x', $this->xAxis);
        $this->settingService->setAxis('y', $this->yAxis);

        $this->slotService->generateMap();

        $count = $this->slotService->getCountByType('s,m,l')->count();
        $this->assertEquals($count, 2262);
    }

    /**
     * @test
     */
    public function positiveGenerateMapTest80By80()
    {
        $this->xAxis = 80;
        $this->yAxis = 80;
        $this->settingService->setAxis('x', $this->xAxis);
        $this->settingService->setAxis('y', $this->yAxis);

        $this->slotService->generateMap();

        $count = $this->slotService->getCountByType('s,m,l')->count();
        $this->assertEquals($count, 4056);
    }

    /**
     * @test
     */
    public function positiveGenerateMapTest100By100()
    {
        $this->xAxis = 100;
        $this->yAxis = 100;
        $this->settingService->setAxis('x', $this->xAxis);
        $this->settingService->setAxis('y', $this->yAxis);

        $this->slotService->generateMap();

        $count = $this->slotService->getCountByType('s,m,l')->count();
        $this->assertEquals($count, 6468);
    }
}
