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
        Schema::connection($this->connection)->create('status', function (Blueprint $collection) {
            $collection->unique('status_id');
            $collection->unique('code');
            $collection->index('status_group');
            $collection->index('color');
            $collection->index('label');
            $collection->index('sort');
            $collection->index('is_active');
            $collection->index('created_by');
            $collection->index('modified_by');
        }, [
            'validator' => [
                '$jsonSchema' => [
                    'bsonType' => 'object',
                    'required' => [
                        'status_id',
                        'code',
                        'status_order',
                        'status_group',
                        'name',
                    ],
                    'properties' => [
                        '_id' => [
                            'bsonType' => 'objectId'
                        ],
                        'status_id' => [
                            'bsonType' => 'int',
                            'description' => 'Must be an integer and is required.',
                        ],
                        'status_order' => [
                            'bsonType' => 'int',
                            'description' => 'Must be an integer and is required.',
                        ],
                        'code' => [
                            'bsonType' => 'string',
                            'maxLength' => 50,
                            'description' => "Must be a string and is required.",
                        ],
                        'status_group' => [
                            'bsonType' => 'string',
                            'maxLength' => 50,
                            'description' => "Must be a string and is required.",
                        ],
                        'name' => [
                            'bsonType' => 'string',
                            'maxLength' => 50,
                            'description' => "Must be a string and is required.",
                        ],
                        'color' => [
                            'bsonType' => 'string',
                            'maxLength' => 50,
                            'description' => "Must be a string.",
                        ],
                        'label' => [
                            'bsonType' => 'string',
                            'maxLength' => 50,
                            'description' => "Must be a string.",
                        ],
                        'sort' => [
                            'bsonType' => 'int',
                            'description' => 'Must be an integer.',
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
        Schema::connection($this->connection)->table('status', function (Blueprint $collection) {
            $collection->drop();
        });
    }
};
