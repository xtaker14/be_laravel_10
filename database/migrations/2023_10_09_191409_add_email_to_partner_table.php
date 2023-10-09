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
        Schema::table('partner', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('package_cost');
            $table->string('phone_number')->nullable()->after('package_cost');
            $table->string('email')->nullable()->after('package_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partner', function (Blueprint $table) {
            $table->dropColumn(['email', 'is_active', 'phone_number']);
        });
    }
};
