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
        Schema::create('slots', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('slot_type_id')->index();
            $table->unsignedInteger('x_axis')->index();
            $table->unsignedInteger('y_axis')->index();
            $table->boolean('is_occupied')->default(false);
            $table->timestamps();

            $table->unique([
                'x_axis',
                'y_axis',
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
        Schema::dropIfExists('slots');
    }
};
