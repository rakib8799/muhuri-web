<?php

namespace App\Services\Core;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModelService
{
    abstract public function model(): string;

    public function first()
    {
        return $this->model()::first();
    }

    public function all($orderBy = 'id', $direction = 'desc')
    {
        return $this->model()::orderBy($orderBy, $direction)->get();
    }

    public function find($primaryKey)
    {
        return $this->model()::find($primaryKey);
    }

    public function findOrFail($primaryKey)
    {
        return $this->model()::findOrFail($primaryKey);
    }

    public function delete($primaryKey)
    {
        return $this->model()::destroy($primaryKey);
    }

    public function create(array $validatedData)
    {
        $validatedData['created_by'] = $validatedData['created_by'] ?? auth()->id();
        $validatedData['updated_by'] = $validatedData['updated_by'] ?? auth()->id();
        return $this->model()::create($validatedData);
    }

    public function update(Model $model, array $data): bool
    {
        $data['updated_by'] = auth()->id();
        return $model->update($data);
    }

}
