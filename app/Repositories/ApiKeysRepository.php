<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\ApiKeys;
use App\Interfaces\ApiKeysRepositoryInterface;

class ApiKeysRepository implements ApiKeysRepositoryInterface
{
    public function all()
    {
        return ApiKeys::all();
    }

    public function create($data)
    {
        return ApiKeys::create($data);
    }

    public function update($data, $id)
    {
        $get = ApiKeys::findOrFail($id);
        $get->update($data);
        return $get;
    }

    public function delete($id)
    {
        $get = ApiKeys::findOrFail($id);
        $get->delete();
    }

    public function find($id)
    {
        return ApiKeys::findOrFail($id);
    }

    public function uniqueKeys($requirements)
    {
        $keys = [];
        $unique = false;

        while (!$unique) {
            foreach ($requirements as $req) {
                $key = '';
                if ($req['column'] == 'merchant_id') {
                    $key = strtoupper(Str::random($req['length']));
                } else {
                    $key = bin2hex(random_bytes($req['length']));
                }
                $fullKey = isset($req['prefix']) ? $req['prefix'] . $key : $key;

                $keys[$req['column']] = $fullKey;
            }

            $unique = !ApiKeys::where(function ($query) use ($keys) {
                foreach ($keys as $column => $val) {
                    $query->orWhere($column, $val);
                }
            })->exists();
        }

        return $keys;
    }
    
}
