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
        Schema::table('transferhistory', function (Blueprint $table) {
            $table->foreign(['transfer_id'], 'transferhistory_ibfk_1')->references(['transfer_id'])->on('transfer')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['status_id'], 'transferhistory_ibfk_2')->references(['status_id'])->on('status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transferhistory', function (Blueprint $table) {
            $table->dropForeign('transferhistory_ibfk_1');
            $table->dropForeign('transferhistory_ibfk_2');
        });
    }
};
