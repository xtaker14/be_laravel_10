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
        Schema::table('packageapi', function (Blueprint $table) {
            $table->foreign(['package_id'], 'packageapi_ibfk_1')->references(['package_id'])->on('package')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packageapi', function (Blueprint $table) {
            $table->dropForeign('packageapi_ibfk_1');
        });
    }
};
