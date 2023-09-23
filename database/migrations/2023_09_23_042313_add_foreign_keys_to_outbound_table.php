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
        Schema::table('outbound', function (Blueprint $table) {
            $table->foreign(['hub_id'], 'outbound_ibfk_1')->references(['hub_id'])->on('hub')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['transfer_id'], 'outbound_ibfk_3')->references(['transfer_id'])->on('transfer')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['package_id'], 'outbound_ibfk_4')->references(['package_id'])->on('package')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('outbound', function (Blueprint $table) {
            $table->dropForeign('outbound_ibfk_1');
            $table->dropForeign('outbound_ibfk_3');
            $table->dropForeign('outbound_ibfk_4');
        });
    }
};
