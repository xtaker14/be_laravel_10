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
        Schema::create('transferhistory', function (Blueprint $table) {
            $table->integer('transfer_history_id', true);
            $table->integer('transfer_id')->index('transfer_id_2');
            $table->integer('status_id')->index('status_id');
            $table->dateTime('created_date');
            $table->dateTime('modified_date');
            $table->string('created_by', 100);
            $table->string('modified_by', 100);

            $table->unique(['transfer_id', 'status_id'], 'transfer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transferhistory');
    }
};
