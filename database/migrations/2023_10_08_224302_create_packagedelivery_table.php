<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('packagedelivery', function (Blueprint $table) {
            $table->integer('package_delivery_id', true);
            $table->integer('package_id')->index('package_id');
            $table->integer('package_history_id')->index('package_history_id');
            $table->string('information', 200);
            $table->string('notes', 200)->nullable();
            $table->enum('accept_cod', ['no', 'yes'])->default('no');
            $table->string('e_signature', 200)->nullable();
            $table->string('photo', 200)->nullable();
            $table->dateTime('created_date');
            $table->dateTime('modified_date');
            $table->string('created_by', 100);
            $table->string('modified_by', 100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('packagedelivery');
    }
};
