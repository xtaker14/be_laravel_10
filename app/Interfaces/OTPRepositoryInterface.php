<?php

namespace App\Interfaces;

interface OTPRepositoryInterface
{
    public function all();
    public function generateCode();
    public function create(array $data);
    public function update(array $data, int $id);
    public function delete(int $id);
    public function find(int $id);
    public function latestOTPEntry(int $usersId, string $type);
    public function OTPEntry(int $usersId, string $type);
}
