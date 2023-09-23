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
        Schema::table('servicetype', function (Blueprint $table) {
            $table->foreign(['organization_id'], 'servicetype_ibfk_1')->references(['organization_id'])->on('organization')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servicetype', function (Blueprint $table) {
            $table->dropForeign('servicetype_ibfk_1');
        });
    }
};
