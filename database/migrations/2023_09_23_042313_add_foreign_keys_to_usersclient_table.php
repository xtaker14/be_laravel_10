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
        Schema::table('usersclient', function (Blueprint $table) {
            $table->foreign(['users_id'], 'usersclient_ibfk_1')->references(['users_id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['client_id'], 'usersclient_ibfk_2')->references(['client_id'])->on('client')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usersclient', function (Blueprint $table) {
            $table->dropForeign('usersclient_ibfk_1');
            $table->dropForeign('usersclient_ibfk_2');
        });
    }
};
