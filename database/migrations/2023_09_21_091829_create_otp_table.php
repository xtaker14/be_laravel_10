<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('otp', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('user_id'); 
            $table->string('phone_number', 50);
            $table->string('otp', 10);
            $table->integer('attempts')->default(0);
            $table->string('type', 50)->nullable();
            $table->timestamp('otp_attempts_reset_at')->nullable();
            $table->timestamp('otp_expires_at')->default(\DB::raw('CURRENT_TIMESTAMP')); 
            $table->timestamp('verified_at')->nullable();
            $table->dateTime('created_date');
            $table->dateTime('modified_date');
            $table->string('created_by', 200);
            $table->string('modified_by', 200);

            // $table->foreign('created_by')->references('users_id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp');
    }
};
