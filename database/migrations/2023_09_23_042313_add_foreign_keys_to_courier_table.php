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
        Schema::table('courier', function (Blueprint $table) {
            $table->foreign(['partner_id'], 'courier_ibfk_1')->references(['partner_id'])->on('partner')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['hub_id'], 'courier_ibfk_2')->references(['hub_id'])->on('hub')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['users_id'], 'courier_ibfk_3')->references(['users_id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courier', function (Blueprint $table) {
            $table->dropForeign('courier_ibfk_1');
            $table->dropForeign('courier_ibfk_2');
            $table->dropForeign('courier_ibfk_3');
        });
    }
};
