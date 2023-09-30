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
        Schema::table('subdistrict', function (Blueprint $table) {
            $table->foreign(['district_id'], 'subdistrict_ibfk_1')->references(['district_id'])->on('district')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subdistrict', function (Blueprint $table) {
            $table->dropForeign('subdistrict_ibfk_1');
        });
    }
};
