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
        Schema::table('usershub', function (Blueprint $table) {
            $table->foreign(['users_id'], 'usershub_ibfk_1')->references(['users_id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['hub_id'], 'usershub_ibfk_2')->references(['hub_id'])->on('hub')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usershub', function (Blueprint $table) {
            $table->dropForeign('usershub_ibfk_1');
            $table->dropForeign('usershub_ibfk_2');
        });
    }
};
