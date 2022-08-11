<?php

namespace Database\Seeders;

use App\Models\Slot\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SlotTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $slotTypes = [
            [
                'code' => 'S',
                'name' => 'Small',
            ],
            [
                'code' => 'M',
                'name' => 'Medium',
            ],
            [
                'code' => 'L',
                'name' => 'Large',
            ],
        ];

        foreach ($slotTypes as $slotType) {
            Type::firstOrCreate($slotType);
        }
    }
}
