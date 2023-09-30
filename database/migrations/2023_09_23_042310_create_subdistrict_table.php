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
        Schema::create('subdistrict', function (Blueprint $table) {
            $table->integer('subdistrict_id', true);
            $table->integer('district_id')->index('district_id');
            $table->string('name', 50);
            $table->boolean('is_active')->default(true);
            $table->dateTime('created_date');
            $table->dateTime('modified_date');
            $table->string('created_by', 200);
            $table->string('modified_by', 200);

            $table->unique(['district_id', 'name'], 'unique_subdistrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subdistrict');
    }
};
