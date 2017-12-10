<?php

namespace App\Repositories;

use App\Models\Comment;

class CommentRepository extends AbstractRepository
{
    public function __construct(Comment $model)
    {
        $this->model = $model;

        parent::__construct($model);
    }

    public function getAllOrderedComments()
    {
        return $this->model->select('*')->where('level', '>', 0)->orderBy('left')->get()->toArray();
    }

    public function deleteAll($where)
    {
        $this->model->where($where)->delete();
    }
}