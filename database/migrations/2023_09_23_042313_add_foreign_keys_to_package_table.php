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
        Schema::table('package', function (Blueprint $table) {
            $table->foreign(['client_id'], 'package_ibfk_1')->references(['client_id'])->on('client')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['hub_id'], 'package_ibfk_2')->references(['hub_id'])->on('hub')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['service_type_id'], 'package_ibfk_3')->references(['service_type_id'])->on('servicetype')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['status_id'], 'package_ibfk_4')->references(['status_id'])->on('status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package', function (Blueprint $table) {
            $table->dropForeign('package_ibfk_1');
            $table->dropForeign('package_ibfk_2');
            $table->dropForeign('package_ibfk_3');
            $table->dropForeign('package_ibfk_4');
        });
    }
};
