<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('packagedelivery', function (Blueprint $table) {
            $table->foreign(['package_id'], 'packagedelivery_ibfk_1')->references(['package_id'])->on('package')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['package_history_id'], 'packagedelivery_ibfk_2')->references(['package_history_id'])->on('packagehistory')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('packagedelivery', function (Blueprint $table) {
            $table->dropForeign('packagedelivery_ibfk_1');
            $table->dropForeign('packagedelivery_ibfk_2');
        });
    }
};
