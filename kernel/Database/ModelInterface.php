<?php

namespace App\Kernel\Database;

interface ModelInterface
{
    public static function getAll(): array;
    public static function create(array $data): int;
    public static function update(array $data, int $id): bool;
    public static function updateMany(array $data, array $ids): bool;
    public static function delete(int $id): bool;
    public static function deleteMany(array $ids): bool;
}