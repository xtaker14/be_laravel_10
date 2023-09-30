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
        Schema::table('inbounddetail', function (Blueprint $table) {
            $table->foreign(['inbound_id'], 'inbounddetail_ibfk_1')->references(['inbound_id'])->on('inbound')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['package_id'], 'inbounddetail_ibfk_2')->references(['package_id'])->on('package')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inbounddetail', function (Blueprint $table) {
            $table->dropForeign('inbounddetail_ibfk_1');
            $table->dropForeign('inbounddetail_ibfk_2');
        });
    }
};
