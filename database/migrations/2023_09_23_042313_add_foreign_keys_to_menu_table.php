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
        Schema::table('menu', function (Blueprint $table) {
            $table->foreign(['parent_id'], 'menu_ibfk_1')->references(['menu_id'])->on('menu')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['feature_id'], 'menu_ibfk_2')->references(['feature_id'])->on('feature')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['permission_id'], 'menu_ibfk_3')->references(['permission_id'])->on('permission')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu', function (Blueprint $table) {
            $table->dropForeign('menu_ibfk_1');
            $table->dropForeign('menu_ibfk_2');
            $table->dropForeign('menu_ibfk_3');
        });
    }
};
