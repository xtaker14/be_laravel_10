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
        Schema::table('routingdetail', function (Blueprint $table) {
            $table->foreign(['routing_id'], 'routingdetail_ibfk_1')->references(['routing_id'])->on('routing')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['package_id'], 'routingdetail_ibfk_2')->references(['package_id'])->on('package')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('routingdetail', function (Blueprint $table) {
            $table->dropForeign('routingdetail_ibfk_1');
            $table->dropForeign('routingdetail_ibfk_2');
        });
    }
};
