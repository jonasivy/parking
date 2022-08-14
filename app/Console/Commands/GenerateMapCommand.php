<?php

namespace App\Console\Commands;

use App\Models\Slot;
use App\Models\Transaction;
use App\Services\SettingService;
use App\Services\SlotService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateMapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parking:generate {--xAxis=} {--yAxis=} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate map at random.';

    /**
     * @param \App\Services\SettingService $settingService
     * @param \App\Services\SlotService $slotService
     * @return void
     */
    public function __construct(SettingService $settingService, SlotService $slotService)
    {
        $this->settingService = $settingService;
        $this->slotService = $slotService;
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

        if ($this->slotService->dataExists()) {
            if (empty($yAxis = $this->option('yAxis'))) {
                $toFlush = $this->ask('Data already exists, do you want to refresh slots and txns? (default: no)');
            }

            if ('yes' !== $toFlush) {
                $this->error('Generate new map is cancelled by user.');
                return 1;
            }

            Slot::truncate();
            Transaction::truncate();
        }

        $startTime = Carbon::now();
        $slotTypes = $this->slotService->generateMap();
        $endTime = Carbon::now();
        $timeInMillieSeconds = $endTime->diffInMilliseconds($startTime) / 1000;

        $this->info("Auto generate map completed within {$timeInMillieSeconds} seconds.");

        $totalCount = 0;
        foreach ($slotTypes as $slotType => $slots) {
            $count = count($slots);
            $totalCount += $count;
            $this->info($slotType . ': ' . $count);
        }

        $this->info("Total of {$totalCount} slots.");
    }
}
