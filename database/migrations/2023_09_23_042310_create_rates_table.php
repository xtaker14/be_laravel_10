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
        Schema::create('rates', function (Blueprint $table) {
            $table->integer('rates_id', true);
            $table->integer('service_type_id')->index('service_type_id_2');
            $table->integer('origin_city_id')->index('origin_city_id');
            $table->integer('destination_city_id')->index('destination_city_id');
            $table->boolean('is_cod');
            $table->decimal('publish_price', 10, 0);
            $table->integer('maximum_delivered');
            $table->dateTime('created_date');
            $table->dateTime('modified_date');
            $table->string('created_by', 100);
            $table->string('modified_by', 100);

            $table->unique(['service_type_id', 'origin_city_id', 'destination_city_id'], 'service_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rates');
    }
};
