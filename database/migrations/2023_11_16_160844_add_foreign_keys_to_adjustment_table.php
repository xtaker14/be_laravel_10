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
        Schema::table('adjustment', function (Blueprint $table) {
            $table->foreign(['status_from'], 'adjustment_ibfk_1')->references(['status_id'])->on('status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['status_to'], 'adjustment_ibfk_2')->references(['status_id'])->on('status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adjustment', function (Blueprint $table) {
            $table->dropForeign('adjustment_ibfk_1');
            $table->dropForeign('adjustment_ibfk_2');
        });
    }
};
