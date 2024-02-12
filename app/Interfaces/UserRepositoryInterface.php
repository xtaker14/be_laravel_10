<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function dataTableUser();
    public function all();
    public function create(array $data);
    public function update(array $data, int $id);
    public function delete(int $id);
    public function find(int $id);
}