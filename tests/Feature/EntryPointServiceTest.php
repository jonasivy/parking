<?php

namespace Tests\Feature;

use App\Services\SettingService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class EntryPointServiceTest extends TestCase
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

        $this->settingService = app()->make(SettingService::class);
        $this->xAxis = rand(10, 100);
        $this->yAxis = rand(10, 100);
        $this->settingService->setAxis('x', $this->xAxis);
        $this->settingService->setAxis('y', $this->yAxis);
    }

    /**
     * @test
     */
    public function positiveTestEntryTopHorizontal()
    {
        $this->xAxis = rand(10, 100);
        $this->yAxis = rand(10, 100);
        $this->settingService->setAxis('x', $this->xAxis);
        $this->settingService->setAxis('y', $this->yAxis);

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
    }

    /**
     * @test
     */
    public function positiveTestEntryBottomHorizontal()
    {
        $this->xAxis = rand(10, 100);
        $this->yAxis = rand(10, 100);
        $this->settingService->setAxis('x', $this->xAxis);
        $this->settingService->setAxis('y', $this->yAxis);

        foreach (range(1, $this->xAxis) as $x) {
            $params = [
                'x-axis' => $x,
                'y-axis' => $this->settingService->getAxis('y')->value,
            ];

            $response = $this->withoutMiddleware()->post(route('entry-point.store'), $params, $this->headers);
            $response->assertStatus(201)
                ->assertJsonFragment([
                    'x-axis' => $x,
                ])
                ->assertJsonFragment([
                    'y-axis' => $this->yAxis,
                ]);
        }
    }

    /**
     * @test
     */
    public function positiveTestEntryLeftVertical()
    {
        $this->xAxis = rand(10, 100);
        $this->yAxis = rand(10, 100);
        $this->settingService->setAxis('x', $this->xAxis);
        $this->settingService->setAxis('y', $this->yAxis);

        foreach (range(1, $this->yAxis) as $y) {
            $params = [
                'x-axis' => $this->settingService->getAxis('x')->value,
                'y-axis' => $y,
            ];

            $response = $this->withoutMiddleware()->post(route('entry-point.store'), $params, $this->headers);
            $response->assertStatus(201)
                ->assertJsonFragment([
                    'x-axis' => $this->xAxis,
                ])
                ->assertJsonFragment([
                    'y-axis' => $y,
                ]);
        }
    }

    /**
     * @test
     */
    public function positiveTestEntryRightVertical()
    {
        $this->xAxis = rand(10, 100);
        $this->yAxis = rand(10, 100);
        $this->settingService->setAxis('x', $this->xAxis);
        $this->settingService->setAxis('y', $this->yAxis);

        foreach (range(1, $this->yAxis) as $y) {
            $params = [
                'x-axis' => 1,
                'y-axis' => $y,
            ];

            $response = $this->withoutMiddleware()->post(route('entry-point.store'), $params, $this->headers);
            $response->assertStatus(201)
                ->assertJsonFragment([
                    'x-axis' => 1,
                ])
                ->assertJsonFragment([
                    'y-axis' => $y,
                ]);
        }
    }

    /**
     * @test
     */
    public function negativeTestInvalidAxisY()
    {
        $this->xAxis = rand(10, 20);
        $this->yAxis = rand(10, 20);
        $this->settingService->setAxis('x', $this->xAxis);
        $this->settingService->setAxis('y', $this->yAxis);

        foreach (range(2, $this->yAxis - 1) as $y) {
            foreach (range(2, $this->xAxis -1) as $x) {
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
        $this->xAxis = rand(10, 20);
        $this->yAxis = rand(10, 20);
        $this->settingService->setAxis('x', $this->xAxis);
        $this->settingService->setAxis('y', $this->yAxis);

        foreach (range(2, $this->xAxis -1) as $x) {
            foreach (range(2, $this->yAxis - 1) as $y) {
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
    public function positiveTestEntryPointDelete()
    {
        $this->xAxis = rand(10, 100);
        $this->yAxis = rand(10, 100);
        $this->settingService->setAxis('x', $this->xAxis);
        $this->settingService->setAxis('y', $this->yAxis);

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

            $response = json_decode($response->getContent());
            $response = $this->withoutMiddleware()
                ->delete(route('entry-point.destroy', [ 'entryPoint' => $response->id ]), $this->headers);
            $response->assertStatus(200)
                ->assertJsonFragment([
                    'message' => 'Deleted',
                ]);
        }
    }

    /**
     * @test
     */
    public function negativeTestEntryPointDelete()
    {
        $this->xAxis = rand(10, 100);
        $this->yAxis = rand(10, 100);
        $this->settingService->setAxis('x', $this->xAxis);
        $this->settingService->setAxis('y', $this->yAxis);

        foreach (range(1, 10) as $x) {
            $response = $this->delete(route('entry-point.destroy', [ 'entryPoint' => $x ]), [], $this->headers);
            $response->assertStatus(404)
                ->assertJsonFragment([
                    'message' => 'Record not found.',
                ]);
        }
    }
}
