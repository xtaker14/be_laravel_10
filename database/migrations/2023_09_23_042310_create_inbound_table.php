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
        Schema::create('inbound', function (Blueprint $table) {
            $table->integer('inbound_id', true);
            $table->integer('hub_id')->index('hub_id');
            $table->integer('routing_id')->index('routing_id')->nullable();
            $table->integer('transfer_id')->nullable()->index('transfer_id');
            $table->string('code', 50)->unique('code');
            $table->integer('inbound_type_id')->index('inbound_type_id');
            $table->string('courier_name', 100)->nullable();
            $table->string('driver_name', 100)->nullable();
            $table->string('driver_phone', 50)->nullable();
            $table->string('vehicle_type', 50)->nullable();
            $table->string('vehicle_number', 50)->nullable();
            $table->string('notes', 500)->nullable();
            $table->dateTime('created_date');
            $table->dateTime('modified_date');
            $table->string('created_by', 100);
            $table->string('modified_by', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inbound');
    }
};
