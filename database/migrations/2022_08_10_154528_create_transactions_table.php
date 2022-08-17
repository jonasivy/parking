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
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->index();
            $table->string('txn_id')->index();
            $table->string('txn_ref_id')->index();
            $table->unsignedInteger('entry_point_id');
            $table->unsignedInteger('slot_id');
            $table->unsignedTinyInteger('slot_type_id');
            $table->unsignedTinyInteger('vehicle_type_id');
            $table->string('plate_no');
            $table->decimal('initial_parking_fee', 20, 6);
            $table->decimal('succeeding_parking_fee', 20, 6)->nullable();
            $table->decimal('day_fee', 20, 6)->nullable();
            $table->unsignedBigInteger('parked_log_id')->index();
            $table->timestamp('parked_at');
            $table->unsignedBigInteger('unparked_log_id')->index()->nullable();
            $table->timestamp('unparked_at')->nullable();
            $table->tinyInteger('status_flag')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
