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
        Schema::table('spotarea', function (Blueprint $table) {
            $table->foreign(['spot_id'], 'spotarea_ibfk_1')->references(['spot_id'])->on('spot')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['district_id'], 'spotarea_ibfk_2')->references(['district_id'])->on('district')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('spotarea', function (Blueprint $table) {
            $table->dropForeign('spotarea_ibfk_1');
            $table->dropForeign('spotarea_ibfk_2');
        });
    }
};
