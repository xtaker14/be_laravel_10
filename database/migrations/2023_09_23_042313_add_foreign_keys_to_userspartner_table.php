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
        Schema::table('userspartner', function (Blueprint $table) {
            $table->foreign(['users_id'], 'userspartner_ibfk_1')->references(['users_id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['partner_id'], 'userspartner_ibfk_2')->references(['partner_id'])->on('partner')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('userspartner', function (Blueprint $table) {
            $table->dropForeign('userspartner_ibfk_1');
            $table->dropForeign('userspartner_ibfk_2');
        });
    }
};
