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
        Schema::create('users', function (Blueprint $table) {
            $table->integer('users_id', true);
            $table->integer('role_id')->index('role_id');
            $table->string('type', 50);
            $table->string('full_name', 100)->unique('unique_full_name');
            $table->string('email', 100)->unique('unique_email');
            $table->string('password', 50);
            $table->boolean('is_active')->default(true);
            $table->string('picture', 200)->nullable();
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
        Schema::dropIfExists('users');
    }
};
