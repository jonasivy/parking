<?php

namespace Database\Seeders;

use App\Models\Vehicle\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vehicleTypes = [
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

        foreach ($vehicleTypes as $vehicleType) {
            Type::firstOrCreate($vehicleType);
        }
    }
}
