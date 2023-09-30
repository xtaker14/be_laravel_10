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
        Schema::create('reconcile', function (Blueprint $table) {
            $table->integer('reconcile_id', true);
            $table->integer('routing_id')->index('routing_id');
            $table->string('code', 50)->unique('code');
            $table->string('unique_number', 50)->unique('unique_number');
            $table->decimal('total_deposit', 10, 0);
            $table->decimal('actual_deposit', 10, 0);
            $table->decimal('remaining_deposit', 10, 0);
            $table->dateTime('created_date');
            $table->dateTime('modified_date');
            $table->string('created_by', 100);
            $table->string('modified_by', 100);

            $table->unique(['routing_id'], 'routing_id_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reconcile');
    }
};
