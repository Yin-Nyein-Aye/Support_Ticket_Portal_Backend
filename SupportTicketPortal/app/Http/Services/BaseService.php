<?php

namespace App\Http\Services;

use App\Http\Contracts\BaseInterface;

/**
 * @property BaseInterface $repository
 */
class BaseService
{
    protected $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function all()
    {
        return $this->repository->all();
    }

    public function find(int $id, array $includes = [])
    {
        return $this->repository->find($id, $includes);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }
}
