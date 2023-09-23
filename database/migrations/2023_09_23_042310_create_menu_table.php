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
        Schema::create('menu', function (Blueprint $table) {
            $table->integer('menu_id', true);
            $table->integer('parent_id')->nullable()->index('parent_id');
            $table->integer('feature_id')->nullable()->index('feature_id');
            $table->integer('permission_id')->nullable()->index('permission_id');
            $table->string('name', 50)->unique('unique_name');
            $table->string('description', 200)->nullable();
            $table->integer('sequence');
            $table->string('image_url', 200)->nullable();
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
        Schema::dropIfExists('menu');
    }
};
