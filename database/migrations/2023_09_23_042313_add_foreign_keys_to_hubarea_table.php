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
        Schema::table('hubarea', function (Blueprint $table) {
            $table->foreign(['hub_id'], 'hubarea_ibfk_1')->references(['hub_id'])->on('hub')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['city_id'], 'hubarea_ibfk_2')->references(['city_id'])->on('city')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hubarea', function (Blueprint $table) {
            $table->dropForeign('hubarea_ibfk_1');
            $table->dropForeign('hubarea_ibfk_2');
        });
    }
};
