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
        Schema::table('organizationdetail', function (Blueprint $table) {
            $table->foreign(['organization_id'], 'organizationdetail_ibfk_1')->references(['organization_id'])->on('organization')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['country_id'], 'organizationdetail_ibfk_2')->references(['country_id'])->on('country')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['province_id'], 'organizationdetail_ibfk_3')->references(['province_id'])->on('province')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizationdetail', function (Blueprint $table) {
            $table->dropForeign('organization_detail_ibfk_1');
            $table->dropForeign('organization_detail_ibfk_2');
            $table->dropForeign('organization_detail_ibfk_3');
        });
    }
};
