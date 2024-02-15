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
        Schema::connection($this->connection)->create('otp', function (Blueprint $collection) {
            $collection->unique('phone_number');
            $collection->index('attempts');
            $collection->index('type');
            $collection->index('otp_attempts_reset_at');
            $collection->index('otp_expires_at');
            $collection->index('verified_at');
            $collection->index('created_by');
            $collection->index('modified_by');
        }, [
            'validator' => [
                '$jsonSchema' => [
                    'bsonType' => 'object',
                    'required' => [
                        'phone_number',
                        'otp',
                        'attempts',
                        'otp_expires_at',
                    ],
                    'properties' => [
                        '_id' => [
                            'bsonType' => 'objectId'
                        ],
                        'phone_number' => [
                            'bsonType' => 'int',
                            'description' => 'Must be an integer and is required.',
                        ],
                        'otp' => [
                            'bsonType' => 'string',
                            'maxLength' => 255,
                            'description' => "Must be a string and is required.",
                        ],
                        'attempts' => [
                            'bsonType' => 'int',
                            'description' => 'Must be an integer and is required.',
                        ],
                        'type' => [
                            'bsonType' => 'string',
                            'maxLength' => 50,
                            'description' => "Must be a string and is required.",
                        ],
                        'otp_attempts_reset_at' => [
                            'bsonType' => 'date',
                            'description' => "Must be a date.",
                        ],
                        'otp_expires_at' => [
                            'bsonType' => 'date',
                            'description' => "Must be a date and is required.",
                        ],
                        'verified_at' => [
                            'bsonType' => 'date',
                            'description' => "Must be a date.",
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
        Schema::connection($this->connection)->table('otp', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};
