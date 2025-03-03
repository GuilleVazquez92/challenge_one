<?php

namespace App\Services;

use App\Repositories\Eloquent\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\NotFoundException;
use App\Exceptions\InternalErrorException;
use App\Exceptions\InvalidFiltersException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Exception;


abstract class BaseService
{
    protected $repository;

    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(Request $request): LengthAwarePaginator
    {
        try {
            $filters = $request->query('filters', []);
            $sortBy = $request->query('sortBy', 'id');
            $order = $request->query('order', 'asc');
            $perPage = (int) $request->query('perPage', 10);

            return $this->repository->getAll($filters, $sortBy, $order, $perPage);
        } catch (QueryException $e) {
            throw new InvalidFiltersException("Error in filters: Please check that the fields are correct.");
        } catch (Exception $e) {
            throw new InternalErrorException("Error retrieving records: " . $e->getMessage());
        }
    }

    public function getById(int $id): Model
    {
        try {
            return $this->repository->getById($id);
        } catch (ModelNotFoundException $e) {
            throw new NotFoundException("The resource with ID {$id} was not found.");
        } catch (Exception $e) {
            throw new InternalErrorException("Error retrieving the resource: " . $e->getMessage());
        }
    }


    public function create(array $data): Model
    {
        try {
            return $this->repository->create($data);
        } catch (Exception $e) {
            throw new InternalErrorException("Error retrieving the resource: " . $e->getMessage());
        }
    }

    public function update(int $id, array $data): Model
    {
        try {
            return $this->repository->update($id, $data);
        } catch (ModelNotFoundException $e) {
            throw new NotFoundException("The resource with ID {$id} was not found.");
        } catch (Exception $e) {
            throw new InternalErrorException("Error retrieving the resource: " . $e->getMessage());
        }
    }

    public function delete(int $id): bool
    {
        try {
            return $this->repository->delete($id);
        } catch (ModelNotFoundException $e) {
            throw new NotFoundException("The resource with ID {$id} was not found.");
        } catch (Exception $e) {
            throw new InternalErrorException("Error retrieving the resource: " . $e->getMessage());
        }
    }
}
