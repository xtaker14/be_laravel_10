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
        Schema::table('packagehistory', function (Blueprint $table) {
            $table->foreign(['package_id'], 'packagehistory_ibfk_1')->references(['package_id'])->on('package')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['status_id'], 'packagehistory_ibfk_2')->references(['status_id'])->on('status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packagehistory', function (Blueprint $table) {
            $table->dropForeign('packagehistory_ibfk_1');
            $table->dropForeign('packagehistory_ibfk_2');
        });
    }
};
