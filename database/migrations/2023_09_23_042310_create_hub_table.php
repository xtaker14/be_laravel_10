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
        Schema::create('hub', function (Blueprint $table) {
            $table->integer('hub_id', true);
            $table->integer('organization_id')->index('organization_id');
            $table->integer('hub_type_id')->index('hub_type_id');
            $table->bigInteger('subdistrict_id')->index('subdistrict_id');
            $table->string('code', 50)->index('code');
            $table->string('name', 100)->index('name');
            $table->string('street_name', 100);
            $table->integer('street_number');
            $table->string('neighbourhood', 50)->nullable();
            $table->integer('postcode');
            $table->string('maps_url', 100);
            $table->string('coordinate', 100);
            $table->boolean('is_active')->default(true);
            $table->dateTime('created_date');
            $table->dateTime('modified_date');
            $table->string('created_by', 100);
            $table->string('modified_by', 100);

            $table->unique(['code'], 'unique_code');
            $table->unique(['name'], 'unique_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hub');
    }
};
