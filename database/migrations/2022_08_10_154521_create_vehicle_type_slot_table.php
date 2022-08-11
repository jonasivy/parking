<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_type_slot', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('vehicle_type_id')->index();
            $table->unsignedInteger('slot_type_id')->index();
            $table->timestamps();

            $table->unique([
                'vehicle_type_id',
                'slot_type_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_type_slot');
    }
};
