<?php

namespace App\Repositories;

use App\Interfaces\TestRepositoryInterface;

use Illuminate\Support\Facades\DB;

class TestRepository implements TestRepositoryInterface
{
    public function checkRelationTable($tableName)
    {
        return DB::select("
            SELECT 
                TABLE_NAME,
                COLUMN_NAME,
                CONSTRAINT_NAME,
                REFERENCED_TABLE_NAME,
                REFERENCED_COLUMN_NAME
            FROM
                INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE
                TABLE_SCHEMA = '" . DB::connection()->getDatabaseName() . "' AND
                REFERENCED_TABLE_NAME IS NOT NULL AND
                TABLE_NAME = '{$tableName}';
        ");
    }
}
