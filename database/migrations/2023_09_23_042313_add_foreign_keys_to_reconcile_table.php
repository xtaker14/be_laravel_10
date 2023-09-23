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
        Schema::table('reconcile', function (Blueprint $table) {
            $table->foreign(['routing_id'], 'reconcile_ibfk_1')->references(['routing_id'])->on('routing')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reconcile', function (Blueprint $table) {
            $table->dropForeign('reconcile_ibfk_1');
        });
    }
};
