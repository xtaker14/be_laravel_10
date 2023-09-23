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
        Schema::table('routing', function (Blueprint $table) {
            $table->foreign(['spot_id'], 'routing_ibfk_1')->references(['spot_id'])->on('spot')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['courier_id'], 'routing_ibfk_2')->references(['courier_id'])->on('courier')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('routing', function (Blueprint $table) {
            $table->dropForeign('routing_ibfk_1');
            $table->dropForeign('routing_ibfk_2');
        });
    }
};
