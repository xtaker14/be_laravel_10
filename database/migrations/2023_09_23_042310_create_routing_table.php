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
        Schema::create('routing', function (Blueprint $table) {
            $table->integer('routing_id', true);
            $table->integer('spot_id')->index('spot_id');
            $table->integer('courier_id')->nullable()->index('courier_id');
            $table->integer('status_id')->index('status_id');
            $table->string('code', 50);
            $table->dateTime('created_date');
            $table->dateTime('modified_date');
            $table->string('created_by', 100);
            $table->string('modified_by', 100);

            $table->unique(['routing_id', 'status_id'], 'routing_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('routing');
    }
};
