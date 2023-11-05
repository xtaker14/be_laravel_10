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
        Schema::create('packageapi', function (Blueprint $table) {
            $table->integer('package_api_id', true);
            $table->integer('package_id')->index('package_id');
            $table->string('action', 255)->index('action');
            $table->integer('status')->comment('0 = processed, 1 = completed, 2 = failed');
            $table->text('message')->nullable();
            $table->dateTime('created_date');
            $table->dateTime('modified_date');
            $table->string('created_by', 100);
            $table->string('modified_by', 100);

            $table->unique(['package_id', 'action'], 'unique1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packageapi');
    }
};
