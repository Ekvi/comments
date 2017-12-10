<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface Repository
{
    public function all($where = [], $select = '*');

    public function one($where = [], $select = '*');

    public function save(Model $model);

    public function update(Model $model);

    public function delete($id);

    public function column($column, $where = []);
}