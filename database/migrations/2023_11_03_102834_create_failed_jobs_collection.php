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
        Schema::connection($this->connection)->create('failed_jobs', function (Blueprint $collection) {
            $collection->unique('id');
            $collection->unique('uuid');
        }, [
            'validator' => [
                '$jsonSchema' => [
                    'bsonType' => 'object',
                    'required' => [
                        'id',
                        'uuid',
                        'connection',
                        'queue',
                        'payload',
                        'exception',
                    ],
                    'properties' => [
                        '_id' => [
                            'bsonType' => 'objectId'
                        ],
                        'id' => [
                            'bsonType' => 'int',
                            'description' => 'Must be an integer and is required.',
                        ],
                        'uuid' => [
                            'bsonType' => 'int',
                            'description' => 'Must be an integer and is required.',
                        ],
                        'connection' => [
                            'bsonType' => 'string',
                            'maxLength' => 250,
                            'description' => "Must be a string and is required.",
                        ],
                        'queue' => [
                            'bsonType' => 'string',
                            'maxLength' => 250,
                            'description' => "Must be a string and is required.",
                        ],
                        'payload' => [
                            'bsonType' => 'string',
                            'description' => "Must be a string and is required.",
                        ],
                        'exception' => [
                            'bsonType' => 'string',
                            'description' => "Must be a string and is required.",
                        ],
                        // ------
                        'failed_at' => [
                            'bsonType' => 'date',
                            'description' => "Must be a date.",
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
        Schema::connection($this->connection)->table('failed_jobs', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};
