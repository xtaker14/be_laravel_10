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
        Schema::table('routingdelivery', function (Blueprint $table) {
            $table->foreign(['routing_id'], 'routingdelivery_ibfk_1')->references(['routing_id'])->on('routing')->onUpdate('NO ACTION')->onDelete('NO ACTION'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routingdelivery', function (Blueprint $table) {
            $table->dropForeign('routingdelivery_ibfk_1'); 
        });
    }
};
