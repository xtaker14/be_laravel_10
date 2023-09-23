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
        Schema::create('routinghistory', function (Blueprint $table) {
            $table->integer('routing_history_id', true);
            $table->integer('routing_id')->index('routing_id_2');
            $table->integer('status_id')->index('status_id');
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
        Schema::dropIfExists('routinghistory');
    }
};
