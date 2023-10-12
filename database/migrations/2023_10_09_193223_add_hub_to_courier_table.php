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
            $table->integer('users_id')->index('courier_users_id')->after('partner_id');

            $table->foreign(['users_id'], 'courier_ibfk_2')->references(['users_id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
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
