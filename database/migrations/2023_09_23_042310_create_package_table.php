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
        Schema::create('package', function (Blueprint $table) {
            $table->integer('package_id', true);
            $table->integer('hub_id');
            $table->integer('client_id')->index('client_id');
            $table->integer('service_type_id')->index('service_type_id');
            $table->integer('status_id')->index('status_id');
            $table->integer('position_number')->default(1);
            $table->integer('master_waybill_id')->index('master_waybill_id')->nullable();

            $table->string('tracking_number', 100)->unique('unique_code');
            $table->string('reference_number', 50)->nullable();
            $table->dateTime('request_pickup_date')->nullable();
            $table->string('merchant_name', 200)->nullable();
            $table->string('pickup_name', 200);
            $table->string('pickup_phone', 50);
            $table->string('pickup_email', 50)->nullable();
            $table->string('pickup_address', 200);
            $table->string('pickup_country', 200);
            $table->string('pickup_province', 200);
            $table->string('pickup_city', 200);
            $table->string('pickup_district', 200);
            $table->string('pickup_subdistrict', 200);
            $table->string('pickup_postal_code', 50)->nullable();
            $table->string('pickup_notes', 500)->nullable();
            $table->string('pickup_coordinate', 100)->nullable();
            $table->string('recipient_name', 200);
            $table->string('recipient_phone', 50);
            $table->string('recipient_email', 50)->nullable();
            $table->string('recipient_address', 50);
            $table->string('recipient_country', 200);
            $table->string('recipient_province', 200);
            $table->string('recipient_city', 200);
            $table->string('recipient_district', 200);
            $table->string('recipient_postal_code', 50)->nullable();
            $table->string('recipient_notes', 500)->nullable();
            $table->string('recipient_coordinate', 100)->nullable();
            $table->decimal('package_price', 15, 0)->default(0);
            $table->decimal('is_insurance', 10, 0)->default(0);
            $table->decimal('shipping_price', 10, 0)->default(0);
            $table->decimal('cod_price', 10, 0)->default(0);
            $table->decimal('total_weight', 10)->default(0);
            $table->integer('total_koli')->default(1);
            $table->string('volumetric', 50)->nullable();
            $table->string('notes', 200)->nullable();
            $table->string('created_via', 50);
            $table->dateTime('created_date');
            $table->dateTime('modified_date');
            $table->string('created_by', 100);
            $table->string('modified_by', 100);

            $table->unique(['package_id', 'status_id'], 'package_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package');
    }
};
