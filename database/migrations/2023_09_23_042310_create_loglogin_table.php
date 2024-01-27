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
        Schema::create('loglogin', function (Blueprint $table) {
            $table->integer('log_login_id', true);
            // $table->bigInteger('user_id')->unsigned(); 
            $table->string('ip', 30)->nullable();
            $table->string('browser', 200)->nullable();
            $table->text('location')->nullable();
            $table->text('access_token')->nullable();
            $table->dateTime('created_date');
            $table->dateTime('modified_date');
            $table->string('created_by', 100);
            $table->string('modified_by', 100);
            
            // $table->foreign('created_by')->references('users_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loglogin');
    }
};
