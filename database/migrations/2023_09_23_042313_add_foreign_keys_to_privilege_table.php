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
        Schema::table('privilege', function (Blueprint $table) {
            $table->foreign(['role_id'], 'privilege_ibfk_1')->references(['role_id'])->on('role')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['feature_id'], 'privilege_ibfk_2')->references(['feature_id'])->on('feature')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['permission_id'], 'privilege_ibfk_3')->references(['permission_id'])->on('permission')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('privilege', function (Blueprint $table) {
            $table->dropForeign('privilege_ibfk_1');
            $table->dropForeign('privilege_ibfk_2');
            $table->dropForeign('privilege_ibfk_3');
        });
    }
};
