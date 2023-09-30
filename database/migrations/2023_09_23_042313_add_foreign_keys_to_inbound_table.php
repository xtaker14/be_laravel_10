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
        Schema::table('inbound', function (Blueprint $table) {
            $table->foreign(['hub_id'], 'inbound_ibfk_1')->references(['hub_id'])->on('hub')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['transfer_id'], 'inbound_ibfk_2')->references(['transfer_id'])->on('transfer')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['inbound_type_id'], 'inbound_ibfk_3')->references(['inbound_type_id'])->on('inboundtype')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inbound', function (Blueprint $table) {
            $table->dropForeign('inbound_ibfk_1');
            $table->dropForeign('inbound_ibfk_2');
            $table->dropForeign('inbound_ibfk_3');
        });
    }
};
