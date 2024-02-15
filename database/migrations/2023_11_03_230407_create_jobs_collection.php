<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mongodb';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection($this->connection)->create('jobs', function (Blueprint $collection) {
            $collection->unique('id');
            $collection->index('queue');
            $collection->index('attempts');
        }, [
            'validator' => [
                '$jsonSchema' => [
                    'bsonType' => 'object',
                    'required' => [
                        'id',
                        'queue',
                        'attempts',
                        'payload',
                        'available_at',
                        'created_at',
                    ],
                    'properties' => [
                        '_id' => [
                            'bsonType' => 'objectId'
                        ],
                        'id' => [
                            'bsonType' => 'int',
                            'description' => 'Must be an integer and is required.',
                        ],
                        'queue' => [
                            'bsonType' => 'string',
                            'maxLength' => 250,
                            'description' => "Must be a string and is required.",
                        ],
                        'attempts' => [
                            'bsonType' => 'int',
                            'description' => 'Must be an integer and is required.',
                        ],
                        'payload' => [
                            'bsonType' => 'string',
                            'description' => "Must be a string and is required.",
                        ],

                        // ------
                        'reserved_at' => [
                            'bsonType' => 'date',
                            'description' => "Must be a date.",
                        ],
                        'available_at' => [
                            'bsonType' => 'date',
                            'description' => "Must be a date and is required.",
                        ],
                        'created_at' => [
                            'bsonType' => 'date',
                            'description' => "Must be a date and is required.",
                        ],
                    ]
                ]
            ],
            'validationLevel' => 'strict',
            'validationAction' => 'error'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection($this->connection)->table('jobs', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};
