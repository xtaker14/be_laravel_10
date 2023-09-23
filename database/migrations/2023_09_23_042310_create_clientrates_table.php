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
        Schema::create('clientrates', function (Blueprint $table) {
            $table->integer('client_rates_id', true);
            $table->integer('client_id')->index('client_id_2');
            $table->integer('rates_id')->index('rates_id');
            $table->integer('selling_price');
            $table->dateTime('created_date');
            $table->dateTime('modified_date');
            $table->string('created_by', 100);
            $table->string('modified_by', 100);

            $table->unique(['client_id', 'rates_id'], 'client_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientrates');
    }
};
