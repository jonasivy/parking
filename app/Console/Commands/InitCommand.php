<?php

namespace App\Console\Commands;

use App\Services\SettingService;
use Illuminate\Console\Command;

class InitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parking:init {--xAxis=} {--yAxis=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize parking tiles x and y.';

    /** @var \App\Services\SettingService */
    protected $settingService;

    /**
     * @param \App\Services\SettingService $settingService
     */
    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (empty($xAxis = $this->option('xAxis'))) {
            $xAxis = $this->ask('Define new x-axis value?');
        }

        if (empty($yAxis = $this->option('yAxis'))) {
            $yAxis = $this->ask('Define new y-axis value?');
        }

        $this->settingService->setAxis('x', $xAxis);
        $this->settingService->setAxis('y', $yAxis);
    }
}
