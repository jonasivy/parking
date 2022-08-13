<?php

namespace Tests\Feature;

use App\Services\EntryPointService;
use App\Services\SettingService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EntryPointServiceTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->headers = [
            'Accept' => 'application/json',
        ];
    }

    /**
     * @test
     */
    public function positiveTestEntryTopHorizontal()
    {
        $settingService = app()->make(SettingService::class);

        $xAxis = 10;
        $yAxis = 10;
        $settingService->setAxis('x', $xAxis);
        $settingService->setAxis('y', $yAxis);

        foreach (range(1, $xAxis) as $x) {
            $params = [
                'x-axis' => $x,
                'y-axis' => 1,
            ];

            $response = $this->withoutMiddleware()->post(route('entry-point.store'), $params, $this->headers);
            $response->assertStatus(201)
                ->assertJsonFragment([
                    'x_axis' => $x,
                ])
                ->assertJsonFragment([
                    'y_axis' => 1,
                ]);
        }
    }

    /**
     * @test
     */
    public function positiveTestEntryBottomHorizontal()
    {
        $settingService = app()->make(SettingService::class);

        $xAxis = rand(10, 100);
        $yAxis = rand(10, 100);
        $settingService->setAxis('x', $xAxis);
        $settingService->setAxis('y', $yAxis);

        foreach (range(1, $xAxis) as $x) {
            $params = [
                'x-axis' => $x,
                'y-axis' => $settingService->getAxis('y')->value,
            ];

            $response = $this->withoutMiddleware()->post(route('entry-point.store'), $params, $this->headers);
            $response->assertStatus(201)
                ->assertJsonFragment([
                    'x_axis' => $x,
                ])
                ->assertJsonFragment([
                    'y_axis' => $yAxis,
                ]);
        }
    }

    /**
     * @test
     */
    public function positiveTestEntryLeftVertical()
    {
        $settingService = app()->make(SettingService::class);

        $xAxis = rand(10, 100);
        $yAxis = rand(10, 100);
        $settingService->setAxis('x', $xAxis);
        $settingService->setAxis('y', $yAxis);

        foreach (range(1, $yAxis) as $y) {
            $params = [
                'x-axis' => $settingService->getAxis('x')->value,
                'y-axis' => $y,
            ];

            $response = $this->withoutMiddleware()->post(route('entry-point.store'), $params, $this->headers);
            $response->assertStatus(201)
                ->assertJsonFragment([
                    'x_axis' => $xAxis,
                ])
                ->assertJsonFragment([
                    'y_axis' => $y,
                ]);
        }
    }

    /**
     * @test
     */
    public function positiveTestEntryRightVertical()
    {
        $settingService = app()->make(SettingService::class);

        $xAxis = rand(10, 100);
        $yAxis = rand(10, 100);
        $settingService->setAxis('x', $xAxis);
        $settingService->setAxis('y', $yAxis);

        foreach (range(1, $yAxis) as $y) {
            $params = [
                'x-axis' => 1,
                'y-axis' => $y,
            ];

            $response = $this->withoutMiddleware()->post(route('entry-point.store'), $params, $this->headers);
            $response->assertStatus(201)
                ->assertJsonFragment([
                    'x_axis' => 1,
                ])
                ->assertJsonFragment([
                    'y_axis' => $y,
                ]);
        }
    }

    /**
     * @test
     */
    public function negativeTestInvalidAxisY()
    {
        $settingService = app()->make(SettingService::class);

        $xAxis = rand(10, 100);
        $yAxis = rand(10, 100);
        $settingService->setAxis('x', $xAxis);
        $settingService->setAxis('y', $yAxis);

        foreach (range(2, $yAxis - 1) as $y) {
            foreach (range(2, $xAxis -1) as $x) {
                $params = [
                    'x-axis' => $x,
                    'y-axis' => $y,
                ];
                $response = $this->withoutMiddleware()->post(route('entry-point.store'), $params, $this->headers);
                $response->assertStatus(422)
                    ->assertJsonFragment([
                        'The entry points x' . $params['x-axis'] . ':y' . $params['y-axis'] . ' is not valid.',
                    ]);
            }
        }
    }

    /**
     * @test
     */
    public function negativeTestInvalidAxisX()
    {
        $settingService = app()->make(SettingService::class);

        $xAxis = rand(10, 100);
        $yAxis = rand(10, 100);
        $settingService->setAxis('x', $xAxis);
        $settingService->setAxis('y', $yAxis);

        foreach (range(2, $xAxis -1) as $x) {
            foreach (range(2, $yAxis - 1) as $y) {
                $params = [
                    'x-axis' => $x,
                    'y-axis' => $y,
                ];
                $response = $this->withoutMiddleware()->post(route('entry-point.store'), $params, $this->headers);
                $response->assertStatus(422)
                    ->assertJsonFragment([
                        'The entry points x' . $params['x-axis'] . ':y' . $params['y-axis'] . ' is not valid.',
                    ]);
            }
        }
    }
}
