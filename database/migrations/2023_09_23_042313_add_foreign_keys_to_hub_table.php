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
        Schema::table('hub', function (Blueprint $table) {
            $table->foreign(['hub_type_id'], 'hub_ibfk_1')->references(['hub_type_id'])->on('hubtype')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['subdistrict_id'], 'hub_ibfk_2')->references(['subdistrict_id'])->on('subdistrict')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['organization_id'], 'hub_ibfk_3')->references(['organization_id'])->on('organization')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hub', function (Blueprint $table) {
            $table->dropForeign('hub_ibfk_1');
            $table->dropForeign('hub_ibfk_2');
            $table->dropForeign('hub_ibfk_3');
        });
    }
};
