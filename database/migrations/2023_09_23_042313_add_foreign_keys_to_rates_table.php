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
        Schema::table('rates', function (Blueprint $table) {
            $table->foreign(['service_type_id'], 'rates_ibfk_1')->references(['service_type_id'])->on('servicetype')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['origin_city_id'], 'rates_ibfk_2')->references(['city_id'])->on('city')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['destination_city_id'], 'rates_ibfk_3')->references(['city_id'])->on('city')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rates', function (Blueprint $table) {
            $table->dropForeign('rates_ibfk_1');
            $table->dropForeign('rates_ibfk_2');
            $table->dropForeign('rates_ibfk_3');
        });
    }
};
