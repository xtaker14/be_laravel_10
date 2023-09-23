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
        Schema::table('transferdetail', function (Blueprint $table) {
            $table->foreign(['transfer_id'], 'transferdetail_ibfk_1')->references(['transfer_id'])->on('transfer')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['package_id'], 'transferdetail_ibfk_2')->references(['package_id'])->on('package')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transferdetail', function (Blueprint $table) {
            $table->dropForeign('transferdetail_ibfk_1');
            $table->dropForeign('transferdetail_ibfk_2');
        });
    }
};
