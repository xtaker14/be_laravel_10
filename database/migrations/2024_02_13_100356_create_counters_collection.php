<?php

use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mongodb';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection($this->connection)->create('counters', function (Blueprint $collection) {
            $collection->unique('collection_name');
            $collection->unique('column_name');
            $collection->unique('code');
            $collection->index('sequence_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->table('counters', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};
