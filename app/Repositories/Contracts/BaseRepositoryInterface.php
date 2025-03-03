<?php

namespace App\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function getAll(array $filters = [], string $sortBy = 'id', string $order = 'asc', int $perPage = 10): LengthAwarePaginator;
    public function getById($id): Model;
    public function create(array $data): Model;
    public function update($id, array $data): Model;
    public function delete($id): bool;
}

