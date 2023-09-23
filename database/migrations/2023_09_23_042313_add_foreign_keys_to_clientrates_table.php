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
        Schema::table('clientrates', function (Blueprint $table) {
            $table->foreign(['client_id'], 'clientrates_ibfk_1')->references(['client_id'])->on('client')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['rates_id'], 'clientrates_ibfk_2')->references(['rates_id'])->on('rates')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientrates', function (Blueprint $table) {
            $table->dropForeign('clientrates_ibfk_1');
            $table->dropForeign('clientrates_ibfk_2');
        });
    }
};
