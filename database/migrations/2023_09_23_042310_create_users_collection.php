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
     *
     * @return void
     */
    public function up()
    {
        Schema::connection($this->connection)->create('users', function (Blueprint $collection) {
            $collection->unique('users_id');
            $collection->unique('role_id');
            $collection->index('gender');
            $collection->unique('full_name');
            $collection->unique('username');
            $collection->unique('email');
            $collection->index('is_active');
            $collection->index('created_by');
            $collection->index('modified_by');
        }, [
            'validator' => [
                '$jsonSchema' => [
                    'bsonType' => 'object',
                    'required' => [
                        'users_id',
                        'role_id',
                        'gender',
                        'full_name',
                        'username',
                        'password',
                        'email',
                        'is_active',
                    ],
                    'properties' => [
                        '_id' => [
                            'bsonType' => 'objectId'
                        ],
                        'users_id' => [
                            'bsonType' => 'int',
                            'description' => 'Must be an integer and is required.',
                        ],
                        'role_id' => [
                            'bsonType' => 'int',
                            'description' => 'Must be an integer and is required.',
                        ],
                        'gender' => [
                            'bsonType' => 'string',
                            'maxLength' => 5,
                            'description' => "Must be a string and is required.",
                        ],
                        'full_name' => [
                            'bsonType' => 'string',
                            'maxLength' => 100,
                            'description' => "Must be a string and is required.",
                        ],
                        'username' => [
                            'bsonType' => 'string',
                            'maxLength' => 50,
                            'description' => "Must be a string and is required.",
                        ],
                        'email' => [
                            'bsonType' => 'string',
                            'maxLength' => 20,
                            'description' => "Must be a string and is required.",
                        ],
                        'is_active' => [
                            'bsonType' => 'string',
                            'maxLength' => 10,
                            'description' => "Must be a string and is required.",
                        ],
                        'password' => [
                            'bsonType' => 'string',
                            'maxLength' => 255,
                            'description' => "Must be a string and is required.",
                        ],
                        'remember_token' => [
                            'bsonType' => 'string',
                            'maxLength' => 255,
                            'description' => "Must be a string.",
                        ],
                        'picture' => [
                            'bsonType' => 'string',
                            'maxLength' => 200,
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
        Schema::connection($this->connection)->table('users', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};
