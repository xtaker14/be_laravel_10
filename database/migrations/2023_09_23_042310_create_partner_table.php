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
        Schema::create('partner', function (Blueprint $table) {
            $table->integer('partner_id', true);
            $table->integer('organization_id')->index('organization_id_3');
            $table->string('code', 50);
            $table->string('name', 100);
            $table->decimal('package_cost', 10)->default(0);
            $table->dateTime('created_date');
            $table->dateTime('modified_date');
            $table->string('created_by', 100);
            $table->string('modified_by', 100);

            $table->unique(['organization_id', 'code'], 'organization_id');
            $table->unique(['organization_id', 'name'], 'organization_id_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partner');
    }
};
