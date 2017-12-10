<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class AbstractRepository implements Repository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all($where = [], $select = '*')
    {
        $query = $this->buildQuery($where, $select);

        return $query->get();
    }

    public function one($where = [], $select = '*')
    {
        $query = $this->buildQuery($where, $select);

        return $query->first();
    }

    public function save(Model $model)
    {
        return $model->save();
    }

    public function update(Model $model)
    {
        return $model->update();
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function column($column, $where = [])
    {
        $query = $this->model;

        if(!empty($where)) {
            $query = $query->where($where);
        }

        return $query->pluck($column)->all();
    }

    protected function buildQuery($where, $select)
    {
        $query = $this->model->select($select);

        if($where) {
            $query = $query->where($where);
        }

        return $query;
    }
}