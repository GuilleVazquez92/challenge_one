<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contracts\BaseRepositoryInterface;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll(array $filters = [], string $sortBy = 'id', string $order = 'asc', int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->query();
        
        foreach ($filters as $key => $value) {
            if (!empty($value)) {

                if (is_array($value)) {
                    $query->whereIn($key, $value);
                } elseif (is_numeric($value)) {
                    $query->where($key, $value);
                } else {
                    $query->where($key, 'LIKE', "%$value%");
                }
            }
        }

        $query->orderBy($sortBy, $order);

        return $query->paginate($perPage);
    }

    public function getById($id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): Model
    {
        $record = $this->model->findOrFail($id);
        $record->update($data);
        return $record;
    }

    public function delete($id): bool
    {
        $record = $this->model->findOrFail($id);
        return $record->delete();
    }
}
