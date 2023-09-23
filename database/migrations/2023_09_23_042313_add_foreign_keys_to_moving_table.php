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
        Schema::table('moving', function (Blueprint $table) {
            $table->foreign(['package_id'], 'moving_ibfk_1')->references(['package_id'])->on('package')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['grid_id'], 'moving_ibfk_2')->references(['grid_id'])->on('grid')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('moving', function (Blueprint $table) {
            $table->dropForeign('moving_ibfk_1');
            $table->dropForeign('moving_ibfk_2');
        });
    }
};
