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
        Schema::table('courier', function (Blueprint $table) {
            $table->string('name')->nullable()->after('code');
            $table->integer('hub_id')->index('courier_hub_id')->after('partner_id');
            $table->boolean('is_active')->default(true)->after('vehicle_number');

            $table->foreign(['hub_id'], 'courier_ibfk_2')->references(['hub_id'])->on('hub')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courier', function (Blueprint $table) {
            $table->dropForeign('courier_ibfk_2');
        });
    }
};
