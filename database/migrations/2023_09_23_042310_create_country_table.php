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
        Schema::create('country', function (Blueprint $table) {
            $table->integer('country_id', true);
            $table->string('code', 2)->unique('unique_code');
            $table->string('name', 50)->unique('unique_name');
            $table->boolean('is_active')->default(true);
            $table->dateTime('created_date');
            $table->dateTime('modified_date');
            $table->string('created_by', 200);
            $table->string('modified_by', 200);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country');
    }
};
