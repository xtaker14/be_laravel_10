<?php

namespace App\Interfaces;

interface ApiKeysRepositoryInterface
{
    public function all();
    public function create(array $data);
    public function update(array $data, int $id);
    public function delete(int $id);
    public function find(int $id);
    public function uniqueKeys(array $requirements);
}
