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
        Schema::create('adjustment', function (Blueprint $table) {
            $table->integer('adjustment_id', true);
            $table->string('code', 50);
            $table->string('type', 50);
            $table->integer('status_from');
            $table->integer('status_to');
            $table->string('reason', 100);
            $table->string('remark', 255)->nullable();
            $table->dateTime('created_date');
            $table->dateTime('modified_date');
            $table->string('created_by', 100);
            $table->string('modified_by', 100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjustment');
    }
};
