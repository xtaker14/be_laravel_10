<?php

namespace App\Interfaces;

interface CountersRepositoryInterface
{
    public function all();
    public function create(array $data);
    public function update(array $data, int $id);
    public function delete(int $id);
    public function find(int $id);
    public function incrementCounter(string $collectionName, string $columnName, array $paramsAdditional);
}
