<?php

namespace App\Http\Repositories;

use App\Http\Contracts\BaseInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->latest()->paginate();
    }

    public function find(int $id, array $includes = [])
    {
        return $this->model->with($includes)->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);

        return $record;
    }

    public function delete(Model $model)
    {
        return $model->delete();
    }

    public function getFiltered(array $filters)
    {
        return $this->model
            ->filter($filters)
            ->with(['creator', 'assignee', 'priority'])
            ->latest()
            ->paginate(10);
    }
}
