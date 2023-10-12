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
        Schema::create('package_upload_history', function (Blueprint $table) {
            $table->integer('upload_id', true);
            $table->string('code', 100);
            $table->string('total_waybill', 10);
            $table->string('filename', 255);
            $table->dateTime('created_date');
            $table->string('created_by', 200);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_upload_history');
    }
};
