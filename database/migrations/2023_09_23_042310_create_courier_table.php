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
        Schema::create('courier', function (Blueprint $table) {
            $table->integer('courier_id', true);
            $table->integer('partner_id')->index('partner_id');
            $table->string('code', 50)->unique('code');
            $table->string('phone', 50);
            $table->string('vehicle_type', 50);
            $table->string('vehicle_number', 50);
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
        Schema::dropIfExists('courier');
    }
};
