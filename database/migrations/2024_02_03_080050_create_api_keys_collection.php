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
        Schema::connection($this->connection)->create('api_keys', function (Blueprint $collection) {
            $collection->unique('api_keys_id');
            $collection->unique('merchant_id');
            $collection->unique('client_key');
            $collection->unique('server_key');
            $collection->index('is_active');
            $collection->index('created_by');
            $collection->index('modified_by');
        }, [
            'validator' => [
                '$jsonSchema' => [
                    'bsonType' => 'object',
                    'required' => [
                        'api_keys_id',
                        'merchant_id',
                        'client_key',
                        'server_key',
                        'is_active', 
                    ],
                    'properties' => [
                        '_id' => [
                            'bsonType' => 'objectId'
                        ],
                        'api_keys_id' => [
                            'bsonType' => 'int',
                            'description' => 'Must be an integer and is required.',
                        ],
                        'merchant_id' => [
                            'bsonType' => 'string',
                            'maxLength' => 255,
                            'description' => "Must be a string and is required.",
                        ],
                        'client_key' => [
                            'bsonType' => 'string',
                            'maxLength' => 255,
                            'description' => "Must be a string and is required.",
                        ],
                        'server_key' => [
                            'bsonType' => 'string',
                            'maxLength' => 255,
                            'description' => "Must be a string and is required.",
                        ],
                        'is_active' => [
                            'bsonType' => 'string',
                            'maxLength' => 10,
                            'description' => "Must be a string and is required.",
                        ],
                        // ------
                        'created_by' => [
                            'bsonType' => 'string',
                            'maxLength' => 100,
                            'description' => "Must be a string.",
                        ],
                        'modified_by' => [
                            'bsonType' => 'string',
                            'maxLength' => 100,
                            'description' => "Must be a string.",
                        ],

                        'created_date' => [
                            'bsonType' => 'date',
                            'description' => "Must be a date.",
                        ],
                        'modified_date' => [
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
        // Schema::dropIfExists('api_keys');
        Schema::connection($this->connection)->table('api_keys', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};
