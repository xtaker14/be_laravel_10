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
        Schema::table('transfer', function (Blueprint $table) {
            $table->foreign(['from_hub_id'], 'transfer_ibfk_1')->references(['hub_id'])->on('hub')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['to_hub_id'], 'transfer_ibfk_2')->references(['hub_id'])->on('hub')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['status_id'], 'transfer_ibfk_3')->references(['status_id'])->on('status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transfer', function (Blueprint $table) {
            $table->dropForeign('transfer_ibfk_1');
            $table->dropForeign('transfer_ibfk_2');
            $table->dropForeign('transfer_ibfk_3');
        });
    }
};
