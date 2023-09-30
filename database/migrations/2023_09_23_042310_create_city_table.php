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
        Schema::create('city', function (Blueprint $table) {
            $table->integer('city_id', true);
            $table->integer('province_id')->index('province_id');
            $table->string('name', 50);
            $table->boolean('is_active')->default(true);
            $table->dateTime('created_date');
            $table->dateTime('modified_date');
            $table->string('created_by', 200);
            $table->string('modified_by', 200);

            $table->unique(['province_id', 'name'], 'unique_city');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('city');
    }
};
