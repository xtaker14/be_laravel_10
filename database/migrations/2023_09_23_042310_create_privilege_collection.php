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
        Schema::connection($this->connection)->create('privilege', function (Blueprint $collection) {
            $collection->unique('privilege_id');
            $collection->unique('role_id');
            $collection->unique('feature_id');
            $collection->unique('permission_id');
            $collection->index('is_active');
            $collection->index('created_by');
            $collection->index('modified_by');
        }, [
            'validator' => [
                '$jsonSchema' => [
                    'bsonType' => 'object',
                    'required' => [
                        'privilege_id',
                        'role_id',
                        'feature_id',
                        'permission_id',
                        'is_active',
                    ],
                    'properties' => [
                        '_id' => [
                            'bsonType' => 'objectId'
                        ],
                        'privilege_id' => [
                            'bsonType' => 'int',
                            'description' => 'Must be an integer and is required.',
                        ],
                        'role_id' => [
                            'bsonType' => 'int',
                            'description' => 'Must be an integer and is required.',
                        ],
                        'feature_id' => [
                            'bsonType' => 'int',
                            'description' => 'Must be an integer and is required.',
                        ],
                        'permission_id' => [
                            'bsonType' => 'int',
                            'description' => 'Must be an integer and is required.',
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
     *
     * @return void
     */
    public function down()
    {
        Schema::connection($this->connection)->table('privilege', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};
