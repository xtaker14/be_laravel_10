<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection($this->connection)->create('loglogin', function (Blueprint $collection) {
            $collection->unique('log_login_id');
            $collection->index('ip');
            $collection->index('browser');
            $collection->index('location');
            $collection->index('created_by');
            $collection->index('modified_by');
        }, [
            'validator' => [
                '$jsonSchema' => [
                    'bsonType' => 'object',
                    'required' => [
                        'log_login_id',
                    ],
                    'properties' => [
                        '_id' => [
                            'bsonType' => 'objectId'
                        ],
                        'log_login_id' => [
                            'bsonType' => 'int',
                            'description' => 'Must be an integer and is required.',
                        ],
                        'ip' => [
                            'bsonType' => 'string',
                            'maxLength' => 30,
                            'description' => "Must be a string.",
                        ],
                        'browser' => [
                            'bsonType' => 'string',
                            'maxLength' => 150,
                            'description' => "Must be a string.",
                        ],
                        'location' => [
                            'bsonType' => 'string',
                            'maxLength' => 250,
                            'description' => "Must be a string.",
                        ],
                        'access_token' => [
                            'bsonType' => 'string',
                            'maxLength' => 255,
                            'description' => "Must be a string.",
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
     *
     * @return void
     */
    public function down()
    {
        Schema::connection($this->connection)->table('loglogin', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};
