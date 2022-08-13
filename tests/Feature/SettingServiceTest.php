<?php

namespace Tests\Feature;

use App\Services\SettingService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SettingServiceTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @return void
     */
    public function setAxisTest()
    {
        $settingService = app()->make(SettingService::class);

        for ($i = 1; $i <= 1000; $i++) {
            $xAxis = rand(1, 999999999);
            $yAxis = rand(1, 999999999);
    
            $settingService->setAxis('x', $xAxis);
            $settingService->setAxis('y', $yAxis);
    
            $this->assertEquals($settingService->getAxis('x')->value, $xAxis);
            $this->assertEquals($settingService->getAxis('y')->value, $yAxis);
        }
    }
}
