<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('routingdelivery', function (Blueprint $table) {
            $table->integer('routing_delivery_id', true);
            $table->integer('routing_id')->index('routing_id');
            $table->integer('delivery');
            $table->integer('delivered');
            $table->integer('undelivered');
            $table->integer('total_delivery');
            $table->decimal('total_cod_price', 15, 0)->default(0);
            $table->decimal('total_shipping_price', 15, 0)->default(0);
            $table->decimal('total_package_price', 15, 0)->default(0);

            $table->dateTime('created_date');
            $table->dateTime('modified_date');
            $table->string('created_by', 100);
            $table->string('modified_by', 100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routingdelivery');
    }
};
