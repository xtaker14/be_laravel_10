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
        Schema::create('status', function (Blueprint $table) {
            $table->integer('status_id', true);
            $table->string('code', 50)->unique('unique_code');
            $table->integer('status_order')->default(1); 
            $table->string('status_group', 50); 
            $table->string('name', 50);
            $table->string('color', 50)->nullable();
            $table->string('label', 50)->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('status');
    }
};
